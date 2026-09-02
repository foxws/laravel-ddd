<?php

declare(strict_types=1);

namespace Foxws\Ddd\Support;

use Illuminate\Support\Str;

class Path
{
    /**
     * Normalize the given namespace to a PSR-4 style prefix.
     */
    public static function toNamespace(string $namespace): string
    {
        return Str::of($namespace)->replace('/', '\\')->trim('\\')->finish('\\')->toString();
    }

    /**
     * Normalize the given path to a composer.json relative path.
     */
    public static function toRelative(string $path, ?string $base = null): string
    {
        $path = Str::of($path)->replace('\\', '/')->trim('/');

        $base = Str::of($base ?? base_path())->replace('\\', '/')->trim('/')->toString();

        if ($path->startsWith($base)) {
            $path = $path->after($base)->trim('/');
        }

        return $path->finish('/')->toString();
    }
}
