<?php

declare(strict_types=1);

namespace Foxws\Ddd\Console\Commands;

class DddMakeCommand extends AbstractDddMakeCommand
{
    /**
     * The command signature.
     */
    protected $signature = 'ddd:make
        {name : The name of the class}
        {--type= : The type of class to generate, e.g. model, action}
        {--domain= : The domain the class belongs to; guessed from the name if omitted}
        {--layer=Domain : The layer to generate into}
        {--force : Create the class even if it already exists}';

    /**
     * The command description.
     */
    protected $description = 'Create a new DDD class';

    /**
     * Get the configured layer for this generator.
     */
    protected function layer(): string
    {
        $layer = $this->option('layer');

        return is_string($layer) ? $layer : 'Domain';
    }
}
