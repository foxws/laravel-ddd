<?php

declare(strict_types=1);

namespace Foxws\Ddd\Support;

use Illuminate\Support\Str;
use SplFileInfo;

class DddEventDiscovery
{
    /**
     * Guess a discovered event listener's class name using each enabled
     * layer's namespace and path, falling back to Laravel's default guesser.
     *
     * @return class-string
     */
    public static function guessClassName(SplFileInfo $file, string $basePath): string
    {
        $class = DddDiscovery::classFromFile($file->getRealPath(), DddDiscovery::layers('discover_events'));

        if ($class !== null) {
            return $class;
        }

        $class = trim(Str::replaceFirst($basePath, '', $file->getRealPath()), DIRECTORY_SEPARATOR);

        return ucfirst(Str::camel(str_replace(
            [DIRECTORY_SEPARATOR, ucfirst(basename(app()->path())).'\\'],
            ['\\', app()->getNamespace()],
            ucfirst(Str::replaceLast('.php', '', $class)),
        )));
    }
}
