<?php

declare(strict_types=1);

namespace Foxws\Ddd\Support;

use Illuminate\Support\Facades\Config;

class DddStubs
{
    /**
     * Get the configured stub path overrides, keyed by type.
     *
     * @return array<string, string>
     */
    public static function get(): array
    {
        return Config::array('ddd.stubs', []);
    }

    /**
     * Resolve the stub file for the given type, preferring a ddd.stubs
     * override, then one published to the application's stubs directory,
     * then the package's bundled stub.
     */
    public static function resolve(string $type): string
    {
        $override = self::get()[$type] ?? null;

        if (is_string($override) && $override !== '') {
            return self::isAbsolutePath($override) ? $override : base_path($override);
        }

        $customPath = base_path("stubs/{$type}.ddd.stub");

        return file_exists($customPath) ? $customPath : __DIR__."/../../stubs/{$type}.ddd.stub";
    }

    /**
     * Determine whether the given path is absolute.
     */
    protected static function isAbsolutePath(string $path): bool
    {
        return (bool) preg_match('/^(?:\/|[A-Za-z]:[\\\\\/])/', $path);
    }
}
