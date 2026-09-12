<?php

declare(strict_types=1);

namespace Foxws\Ddd;

use Foxws\Ddd\Console\Commands\DddInstallCommand;
use Foxws\Ddd\Console\Commands\DddMakeCommand;
use Foxws\Ddd\Console\Commands\DddMakeDomainCommand;
use Foxws\Ddd\Console\Commands\DddMakeFoundationCommand;
use Foxws\Ddd\Console\Commands\DddMakeModuleCommand;
use Foxws\Ddd\Console\Commands\DddMakeSupportCommand;
use Illuminate\Foundation\Events\DiscoverEvents;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use SplFileInfo;

class DddServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ddd.php', 'ddd');

        // Registered in `register()`, not `boot()`, so the guesser is in
        // place before the host app's own EventServiceProvider boots and
        // calls discoverEvents() — all providers register() before any boot().
        $this->configureEventDiscovery();
    }

    /**
     * Resolve discovered event listener class names using each configured
     * layer's namespace and path, falling back to Laravel's default guesser.
     */
    protected function configureEventDiscovery(): void
    {
        DiscoverEvents::guessClassNamesUsing($this->guessEventClassName(...));
    }

    /**
     * @return class-string
     */
    protected function guessEventClassName(SplFileInfo $file, string $basePath): string
    {
        if (config('ddd.discover_events', true)) {
            foreach (config('ddd.layers', []) as $layer) {
                $layerPath = base_path(trim((string) ($layer['path'] ?? ''), '/'));

                if ($layerPath === '' || ! str_starts_with($file->getRealPath(), $layerPath)) {
                    continue;
                }

                $class = trim(Str::replaceFirst($layerPath, '', $file->getRealPath()), DIRECTORY_SEPARATOR);

                return $layer['namespace'].'\\'.str_replace(
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

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/ddd.php' => config_path('ddd.php'),
        ], ['ddd', 'ddd-config']);

        $this->publishes([
            __DIR__.'/../stubs' => base_path('stubs'),
        ], ['ddd', 'ddd-stubs']);

        $this->commands([
            DddInstallCommand::class,
            DddMakeCommand::class,
            DddMakeDomainCommand::class,
            DddMakeFoundationCommand::class,
            DddMakeModuleCommand::class,
            DddMakeSupportCommand::class,
        ]);
    }
}
