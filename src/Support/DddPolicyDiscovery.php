<?php

declare(strict_types=1);

namespace Foxws\Ddd\Support;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use ReflectionClass;

class DddPolicyDiscovery
{
    /**
     * Guess the policy class names for the given model: next to it in its layer,
     * then Laravel's default guesses, then the pluralized domain. Gate uses the
     * first that exists, so a model's own policy always comes first.
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
            ...static::pluralPolicyNames($class, $layers),
        ]));
    }

    /**
     * Guess the policy next to the model in the enabled layers, e.g.
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

        return $names;
    }

    /**
     * Guess the policy in the pluralized domain, e.g. App\Models\User resolves
     * to Domain\Users\Policies\UserPolicy.
     *
     * @param  array<string, array{namespace?: string, path: string}>  $layers
     * @return list<string>
     */
    protected static function pluralPolicyNames(string $class, array $layers): array
    {
        $policies = trim(DddSubstitutions::get()['policy'] ?? 'Policies', '\\');
        $domain = Str::pluralStudly(class_basename($class));

        if ($policies === '' || ! static::canBelongToDomain($class, $domain)) {
            return [];
        }

        $policy = class_basename($class).'Policy';
        $pluralLayers = DddDiscovery::enabledLayerKeys('discover_policies') ?? ['Domain'];
        $names = [];

        foreach (array_intersect_key($layers, array_flip($pluralLayers)) as $key => $layer) {
            $names[] = Path::toNamespace($layer['namespace'] ?? $key)
                .$domain.'\\'.$policies.'\\'.$policy;
        }

        return $names;
    }

    /**
     * A class with the same name in another domain, or in an installed package,
     * is a different model, so it mustn't get this domain's policy.
     */
    protected static function canBelongToDomain(string $class, string $domain): bool
    {
        /** @var array<string, array{namespace?: string, path: string}> $layers */
        $layers = Config::array('ddd.layers', []);

        foreach ($layers as $key => $layer) {
            $namespace = Path::toNamespace($layer['namespace'] ?? $key);

            if (! str_starts_with($class, $namespace)) {
                continue;
            }

            $segments = explode('\\', Str::after($class, $namespace));

            return count($segments) === 1 || $segments[0] === $domain;
        }

        return static::isApplicationClass($class);
    }

    /**
     * Whether the class is declared in the application, outside its vendor folder.
     */
    protected static function isApplicationClass(string $class): bool
    {
        if (! class_exists($class)) {
            return false;
        }

        $file = (new ReflectionClass($class))->getFileName();
        $basePath = realpath(base_path());

        if ($file === false || $basePath === false) {
            return false;
        }

        return str_starts_with($file, $basePath.DIRECTORY_SEPARATOR)
            && ! str_starts_with($file, $basePath.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR);
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
