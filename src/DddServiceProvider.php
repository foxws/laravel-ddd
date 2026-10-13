<?php

declare(strict_types=1);

namespace Foxws\Ddd;

use Foxws\Ddd\Console\Commands\DddInstallCommand;
use Foxws\Ddd\Console\Commands\DddMakeCommand;
use Foxws\Ddd\Console\Commands\DddMakeDomainCommand;
use Foxws\Ddd\Console\Commands\DddMakeFoundationCommand;
use Foxws\Ddd\Console\Commands\DddMakeModuleCommand;
use Foxws\Ddd\Console\Commands\DddMakeSupportCommand;
use Foxws\Ddd\Support\DddEventDiscovery;
use Foxws\Ddd\Support\DddPolicyDiscovery;
use Illuminate\Auth\Access\Gate;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Events\DiscoverEvents;
use Illuminate\Support\ServiceProvider;

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
        DiscoverEvents::guessClassNamesUsing(DddEventDiscovery::guessClassName(...));

        // Registered once the gate resolves, so a guesser set later by the
        // host app (e.g. in its AuthServiceProvider) still takes precedence.
        $this->callAfterResolving(GateContract::class, function (GateContract $gate): void {
            if ($gate instanceof Gate) {
                $gate->guessPolicyNamesUsing(DddPolicyDiscovery::guessPolicyNames(...));
            }
        });
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
