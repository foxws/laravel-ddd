<?php

declare(strict_types=1);

if (! function_exists('layer_path')) {
    /**
     * Get the path to the given DDD layer, as configured in ddd.layers.
     */
    function layer_path(string $layer, string $path = ''): string
    {
        $layerPath = trim((string) config("ddd.layers.{$layer}.path"), '/');

        return base_path($path !== '' ? $layerPath.'/'.ltrim($path, '/') : $layerPath);
    }
}

if (! function_exists('domain_path')) {
    /**
     * Get the path to the Domain layer.
     */
    function domain_path(string $path = ''): string
    {
        return layer_path('Domain', $path);
    }
}

if (! function_exists('modules_path')) {
    /**
     * Get the path to the Modules layer.
     */
    function modules_path(string $path = ''): string
    {
        return layer_path('Modules', $path);
    }
}
