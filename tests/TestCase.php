<?php

declare(strict_types=1);

namespace Foxws\Ddd\Tests;

use Foxws\Ddd\DddServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            DddServiceProvider::class,
        ];
    }
}
