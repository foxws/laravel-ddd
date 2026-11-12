<?php

declare(strict_types=1);

namespace Foxws\Ddd\Support;

use Illuminate\Console\Command;
use ReflectionClass;
use Symfony\Component\Finder\Finder;

class DddCommandDiscovery
{
    /**
     * The console commands in the command folder of every domain, e.g.
     * src/Domain/Users/Commands, in the layers discover_commands applies to.
     *
     * @return list<class-string<Command>>
     */
    public static function commands(): array
    {
        $directories = [];

        foreach (DddDiscovery::typePaths('discover_commands', 'command') as $pattern) {
            $directories = [...$directories, ...(glob($pattern, GLOB_ONLYDIR) ?: [])];
        }

        if ($directories === []) {
            return [];
        }

        $layers = DddDiscovery::layers('discover_commands');

        $commands = [];

        foreach (Finder::create()->files()->name('*.php')->in($directories) as $file) {
            $class = DddDiscovery::classFromFile($file->getRealPath(), $layers);

            if ($class === null || ! class_exists($class)) {
                continue;
            }

            $reflection = new ReflectionClass($class);

            if ($reflection->isSubclassOf(Command::class) && ! $reflection->isAbstract()) {
                $commands[] = $reflection->getName();
            }
        }

        $commands = array_values(array_unique($commands));

        sort($commands);

        return $commands;
    }
}
