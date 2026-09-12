<?php

declare(strict_types=1);

namespace Foxws\Ddd\Console\Commands;

use Foxws\Ddd\Support\DddStubs;
use Foxws\Ddd\Support\DddSubstitutions;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

abstract class AbstractDddMakeCommand extends GeneratorCommand
{
    /**
     * Execute the console command.
     */
    public function handle(): int|bool|null
    {
        $type = $this->option('type');

        if (! is_string($type) || $type === '') {
            $this->components->error('The --type option is required, e.g. --type=action.');

            return self::FAILURE;
        }

        if (! array_key_exists($type, DddSubstitutions::get())) {
            $this->components->error(sprintf(
                'Unknown type "%s". Available types: %s.',
                $type,
                implode(', ', array_keys(DddSubstitutions::get())),
            ));

            return self::FAILURE;
        }

        if (! file_exists($this->resolveStub($type))) {
            $this->components->error("No stub found for type \"{$type}\". Expected stubs/{$type}.ddd.stub, a ddd.stubs entry, or publish your own to base_path('stubs/{$type}.ddd.stub').");

            return self::FAILURE;
        }

        if (config("ddd.layers.{$this->layer()}") === null) {
            $this->components->error("The \"{$this->layer()}\" layer is not configured or has been disabled in ddd.layers.");

            return self::FAILURE;
        }

        $this->type = Str::studly($type);

        return parent::handle();
    }

    /**
     * Get the configured layer for this generator.
     */
    abstract protected function layer(): string;

    /**
     * Get the domain the class belongs to, guessing it from the name if not given.
     */
    protected function domain(): string
    {
        if (is_string($domain = $this->option('domain')) && $domain !== '') {
            return $domain;
        }

        preg_match_all('/[A-Z][a-z0-9]*/', Str::studly($this->getNameInput()), $words);

        return $words[0] === [] ? $this->getNameInput() : (string) end($words[0]);
    }

    /**
     * Get the root namespace for the class.
     */
    protected function rootNamespace(): string
    {
        return rtrim((string) config("ddd.layers.{$this->layer()}.namespace"), '\\').'\\';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        $namespace = rtrim($rootNamespace, '\\').'\\'.Str::studly($this->domain());

        $type = $this->option('type');
        $substitution = DddSubstitutions::get()[is_string($type) ? $type : ''] ?? '';

        return $substitution !== '' ? $namespace.'\\'.$substitution : $namespace;
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     */
    protected function getPath($name): string
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        $layerPath = rtrim((string) config("ddd.layers.{$this->layer()}.path"), '/');

        return base_path($layerPath.'/'.str_replace('\\', '/', $name).'.php');
    }

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string
    {
        $type = $this->option('type');
        $type = is_string($type) ? $type : '';

        return $this->resolveStub($type);
    }

    /**
     * Resolve the stub file for the given type, preferring a ddd.stubs
     * override, then one published to the application's stubs directory.
     */
    protected function resolveStub(string $type): string
    {
        return DddStubs::resolve($type);
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     */
    protected function buildClass($name): string
    {
        return str_replace(
            ['{{ factoryImport }}', '{{factoryImport}}', '{{ factory }}', '{{factory}}'],
            '',
            parent::buildClass($name),
        );
    }
}
