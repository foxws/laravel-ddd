<?php

declare(strict_types=1);

namespace Foxws\Ddd\Support;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use SplFileInfo;

class DddEventDiscovery
{
    /**
     * Guess a discovered event listener's class name using each configured
     * layer's namespace and path, falling back to Laravel's default guesser.
     *
     * @return class-string
     */
    public static function guessClassName(SplFileInfo $file, string $basePath): string
    {
        if (Config::boolean('ddd.discover_events', true)) {
            /** @var array<string, array{namespace?: string, path: string}> $layers */
            $layers = Config::array('ddd.layers', []);

            foreach ($layers as $key => $layer) {
                $layerPath = base_path(rtrim(Path::toRelative($layer['path']), '/'));

                if ($layerPath === '' || ! str_starts_with($file->getRealPath(), $layerPath)) {
                    continue;
                }

                $class = trim(Str::replaceFirst($layerPath, '', $file->getRealPath()), DIRECTORY_SEPARATOR);

                return Path::toNamespace($layer['namespace'] ?? $key).str_replace(
                    DIRECTORY_SEPARATOR, '\\', Str::replaceLast('.php', '', $class),
                );
            }
        }

        $class = trim(Str::replaceFirst($basePath, '', $file->getRealPath()), DIRECTORY_SEPARATOR);

        return ucfirst(Str::camel(str_replace(
            [DIRECTORY_SEPARATOR, ucfirst(basename(app()->path())).'\\'],
            ['\\', app()->getNamespace()],
            ucfirst(Str::replaceLast('.php', '', $class)),
        )));
    }
}
