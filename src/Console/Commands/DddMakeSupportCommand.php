<?php

declare(strict_types=1);

namespace Foxws\Ddd\Console\Commands;

use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'ddd:make-support')]
class DddMakeSupportCommand extends DddMakeCommand
{
    /**
     * The command signature.
     */
    protected $signature = 'ddd:make-support
        {name : The name of the class}
        {--type= : The type of class to generate, e.g. trait, service}
        {--domain= : The domain the class belongs to; guessed from the name if omitted}
        {--force : Create the class even if it already exists}';

    /**
     * The command description.
     */
    protected $description = 'Create a new DDD class in the Support layer';

    /**
     * Get the configured layer for this generator.
     */
    protected function layer(): string
    {
        return 'Support';
    }
}
