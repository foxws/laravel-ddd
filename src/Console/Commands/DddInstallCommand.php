<?php

declare(strict_types=1);

namespace Foxws\Ddd\Console\Commands;

use Foxws\Ddd\Support\ComposerHelper;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use RuntimeException;

class DddInstallCommand extends Command
{
    /**
     * The command signature.
     */
    protected $signature = 'ddd:install
        {--force : Overwrite composer.json entries that already point somewhere else}
        {--no-dump-autoload : Skip regenerating the Composer autoloader}';

    /**
     * The command description.
     */
    protected $description = 'Prepare the application for Domain Driven Design (DDD).';

    public function __construct(protected Filesystem $files)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        /** @var Composer $composerService */
        $composerService = $this->laravel->make('composer');

        $namespaces = ComposerHelper::namespaces();

        $helpersFile = rtrim($namespaces['Foundation\\'], '/').'/Helpers.php';

        $changed = false;
        $conflicts = [];

        try {
            $composerService->modify(function (array $composer) use ($namespaces, $helpersFile, &$changed, &$conflicts) {
                [$composer, $namespacesChanged, $conflicts] = ComposerHelper::mergeNamespaces($composer, $namespaces, (bool) $this->option('force'));
                [$composer, $fileChanged] = ComposerHelper::mergeAutoloadFile($composer, $helpersFile);

                $changed = $namespacesChanged || $fileChanged;

                return $composer;
            });
        } catch (RuntimeException $exception) {
            $this->components->error($exception->getMessage());

            return self::FAILURE;
        }

        foreach ($conflicts as $namespace => $current) {
            $this->components->warn("Skipped \"{$namespace}\": already mapped to \"{$current}\".");
        }

        $this->components->info($changed
            ? 'composer.json updated with the DDD autoload mappings.'
            : 'composer.json already contains the DDD autoload mappings.');

        foreach ($namespaces as $path) {
            $this->files->ensureDirectoryExists(base_path($path));
        }

        if (! $this->files->exists(base_path($helpersFile))) {
            $this->files->put(base_path($helpersFile), "<?php\n\ndeclare(strict_types=1);\n");
        }

        if ($changed && ! $this->option('no-dump-autoload')) {
            $this->components->task('Regenerating the Composer autoloader', fn (): bool => $composerService->dumpAutoloads() === 0);
        }

        $this->components->info('DDD structure is ready.');

        return self::SUCCESS;
    }
}
