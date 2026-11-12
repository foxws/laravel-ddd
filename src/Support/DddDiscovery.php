<?php

declare(strict_types=1);

namespace Foxws\Ddd\Support;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class DddDiscovery
{
    /**
     * Get the configured layers the given discovery option applies to.
     *
     * The option is either a boolean, where true enables every layer, or a
     * list (or comma-separated string) of layer keys, e.g. "Domain,Modules".
     *
     * @return array<string, array{namespace?: string, path: string}>
     */
    public static function layers(string $option): array
    {
        /** @var array<string, array{namespace?: string, path: string}> $layers */
        $layers = Config::array('ddd.layers', []);

        $enabled = static::enabledLayerKeys($option);

        if ($enabled === null) {
            return $layers;
        }

        return array_intersect_key($layers, array_flip($enabled));
    }

    /**
     * Get the layer keys explicitly listed for the given discovery option,
     * or null when it is enabled for every layer.
     *
     * @return list<string>|null
     */
    public static function enabledLayerKeys(string $option): ?array
    {
        $value = Config::get("ddd.{$option}", true);

        if (is_string($value)) {
            $value = filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? explode(',', $value);
        }

        if (is_bool($value) || $value === null) {
            return $value === true ? null : [];
        }

        return array_values(array_filter(array_map(
            fn (mixed $key): string => trim((string) $key),
            (array) $value,
        )));
    }

    /**
     * The glob patterns for the given type's folder in every domain of the
     * layers the discovery option applies to, e.g. src/Domain/{domain}/Listeners.
     *
     * @return list<string>
     */
    public static function typePaths(string $option, string $type): array
    {
        $subfolder = trim(str_replace('\\', '/', DddSubstitutions::get()[$type] ?? ''), '/');

        if ($subfolder === '') {
            return [];
        }

        $paths = [];

        foreach (static::layers($option) as $layer) {
            if (($layerPath = static::layerPath($layer)) !== null) {
                $paths[] = "{$layerPath}/*/{$subfolder}";
            }
        }

        return $paths;
    }

    /**
     * The class a file in one of the given layers declares, following the
     * layer's namespace and path, or null when it isn't in any of them.
     *
     * @param  array<string, array{namespace?: string, path: string}>  $layers
     */
    public static function classFromFile(string $realPath, array $layers): ?string
    {
        foreach ($layers as $key => $layer) {
            $layerPath = static::layerPath($layer);

            if ($layerPath === null) {
                continue;
            }

            $layerPath = (realpath($layerPath) ?: $layerPath).DIRECTORY_SEPARATOR;

            if (! str_starts_with($realPath, $layerPath)) {
                continue;
            }

            $class = Str::of($realPath)
                ->after($layerPath)
                ->replaceLast('.php', '')
                ->replace(DIRECTORY_SEPARATOR, '\\')
                ->toString();

            return Path::toNamespace($layer['namespace'] ?? $key).$class;
        }

        return null;
    }

    /**
     * The absolute path of the given layer, or null when it has no path.
     *
     * @param  array{namespace?: string, path: string}  $layer
     */
    protected static function layerPath(array $layer): ?string
    {
        $relativePath = trim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $layer['path']), DIRECTORY_SEPARATOR);

        return $relativePath !== '' ? base_path($relativePath) : null;
    }
}
