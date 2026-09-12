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

if (! function_exists('foundation_path')) {
    /**
     * Get the path to the Foundation layer.
     */
    function foundation_path(string $path = ''): string
    {
        return layer_path('Foundation', $path);
    }
}

if (! function_exists('support_path')) {
    /**
     * Get the path to the Support layer.
     */
    function support_path(string $path = ''): string
    {
        return layer_path('Support', $path);
    }
}

if (! function_exists('infrastructure_path')) {
    /**
     * Get the path to the Infrastructure layer.
     */
    function infrastructure_path(string $path = ''): string
    {
        return layer_path('Infrastructure', $path);
    }
}

if (! function_exists('integrations_path')) {
    /**
     * Get the path to the Integrations layer.
     */
    function integrations_path(string $path = ''): string
    {
        return layer_path('Integrations', $path);
    }
}
