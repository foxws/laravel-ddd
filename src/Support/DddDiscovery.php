<?php

declare(strict_types=1);

namespace Foxws\Ddd\Support;

use Illuminate\Support\Facades\Config;

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
}
