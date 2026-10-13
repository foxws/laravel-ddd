<?php

declare(strict_types=1);

namespace Foxws\Ddd\Support;

use Illuminate\Support\Str;

class DddPolicyDiscovery
{
    /**
     * Guess the policy class names for the given model using each configured
     * layer's namespace, followed by Laravel's default guesses.
     *
     * @return list<string>
     */
    public static function guessPolicyNames(string $class): array
    {
        $layers = DddDiscovery::layers('discover_policies');

        if ($layers === []) {
            return static::defaultPolicyNames($class);
        }

        return array_values(array_unique([
            ...static::layerPolicyNames($class, $layers),
            ...static::defaultPolicyNames($class),
        ]));
    }

    /**
     * Guess the policy class names using the enabled layers, e.g.
     * Domain\Users\Models\User resolves to Domain\Users\Policies\UserPolicy.
     *
     * @param  array<string, array{namespace?: string, path: string}>  $layers
     * @return list<string>
     */
    protected static function layerPolicyNames(string $class, array $layers): array
    {
        $substitutions = DddSubstitutions::get();

        $models = trim($substitutions['model'] ?? 'Models', '\\');
        $policies = trim($substitutions['policy'] ?? 'Policies', '\\');

        if ($models === '' || $policies === '') {
            return [];
        }

        $policy = class_basename($class).'Policy';
        $classNamespace = Str::beforeLast($class, '\\');

        $names = [];

        foreach ($layers as $key => $layer) {
            $namespace = Path::toNamespace($layer['namespace'] ?? $key);

            if (! str_starts_with($class, $namespace)) {
                continue;
            }

            if (str_contains($classNamespace.'\\', "\\{$models}\\")) {
                $names[] = Str::replaceLast("\\{$models}\\", "\\{$policies}\\", $classNamespace.'\\').$policy;
            }
        }

        $pluralLayers = DddDiscovery::enabledLayerKeys('discover_policies') ?? ['Domain'];

        foreach (array_intersect_key($layers, array_flip($pluralLayers)) as $key => $layer) {
            $names[] = Path::toNamespace($layer['namespace'] ?? $key)
                .Str::pluralStudly(class_basename($class)).'\\'.$policies.'\\'.$policy;
        }

        return $names;
    }

    /**
     * Guess the policy class names the same way Laravel's Gate does by default.
     *
     * @return list<string>
     */
    protected static function defaultPolicyNames(string $class): array
    {
        $policy = class_basename($class).'Policy';
        $classDirname = str_replace('/', '\\', dirname(str_replace('\\', '/', $class)));
        $segments = explode('\\', $classDirname);

        $names = [];

        foreach (range(1, count($segments)) as $index) {
            $names[] = implode('\\', array_slice($segments, 0, $index)).'\\Policies\\'.$policy;
        }

        if (str_contains($classDirname, '\\Models\\')) {
            $names[] = str_replace('\\Models\\', '\\Policies\\', $classDirname).'\\'.$policy;
            $names[] = str_replace('\\Models\\', '\\Models\\Policies\\', $classDirname).'\\'.$policy;
        }

        return array_reverse($names);
    }
}
