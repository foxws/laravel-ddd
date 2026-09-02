<?php

declare(strict_types=1);

namespace Foxws\Ddd;

use Foxws\Ddd\Console\Commands\DddInstallCommand;
use Foxws\Ddd\Console\Commands\DddMakeCommand;
use Foxws\Ddd\Console\Commands\DddMakeDomainCommand;
use Foxws\Ddd\Console\Commands\DddMakeFoundationCommand;
use Foxws\Ddd\Console\Commands\DddMakeModuleCommand;
use Foxws\Ddd\Console\Commands\DddMakeSupportCommand;
use Illuminate\Support\ServiceProvider;

class DddServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ddd.php', 'ddd');
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
