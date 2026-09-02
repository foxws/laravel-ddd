<?php

declare(strict_types=1);

namespace Foxws\Ddd\Support;

use Illuminate\Support\Facades\Config;

class ComposerHelper
{
    /**
     * The PSR-4 namespace to relative path map derived from the package configuration.
     *
     * @return array<string, string>
     */
    public static function namespaces(): array
    {
        $namespaces = [];

        /** @var array<string, array{namespace?: string, path: string}> $layers */
        $layers = Config::array('ddd.layers', []);

        foreach ($layers as $key => $layer) {
            $namespaces[Path::toNamespace($layer['namespace'] ?? $key)] = Path::toRelative($layer['path']);
        }

        $namespaces['Database\\Factories\\'] = 'database/factories/';
        $namespaces['Database\\Seeders\\'] = 'database/seeders/';

        return $namespaces;
    }

    /**
     * Merge the given namespaces into the composer.json autoload.psr-4 map.
     *
     * @param  array<string, mixed>  $composer
     * @param  array<string, string>  $namespaces
     * @return array{0: array<string, mixed>, 1: bool, 2: array<string, string>}
     */
    public static function mergeNamespaces(array $composer, array $namespaces, bool $force = false): array
    {
        $changed = false;
        $conflicts = [];

        foreach ($namespaces as $namespace => $path) {
            $current = $composer['autoload']['psr-4'][$namespace] ?? null;

            if ($current === $path) {
                continue;
            }

            if ($current !== null && ! $force) {
                $conflicts[$namespace] = $current;

                continue;
            }

            $composer['autoload']['psr-4'][$namespace] = $path;
            $changed = true;
        }

        return [$composer, $changed, $conflicts];
    }

    /**
     * Merge the given file into the composer.json autoload.files list.
     *
     * @param  array<string, mixed>  $composer
     * @return array{0: array<string, mixed>, 1: bool}
     */
    public static function mergeAutoloadFile(array $composer, string $file): array
    {
        if (in_array($file, $composer['autoload']['files'] ?? [], true)) {
            return [$composer, false];
        }

        $composer['autoload']['files'][] = $file;

        return [$composer, true];
    }
}
