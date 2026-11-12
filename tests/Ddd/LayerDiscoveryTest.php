<?php

declare(strict_types=1);

use Fixtures\Layer\Users\Commands\SyncUsersCommand;
use Fixtures\Layer\Users\Events\UserInvited;
use Fixtures\Layer\Users\Listeners\SendInvitation;
use Foxws\Ddd\DddServiceProvider;
use Foxws\Ddd\Support\DddCommandDiscovery;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    $fixtures = [
        'Users/Events/UserInvited.php' => <<<'PHP'
            <?php

            namespace Fixtures\Layer\Users\Events;

            class UserInvited
            {
                //
            }
            PHP,
        'Users/Listeners/SendInvitation.php' => <<<'PHP'
            <?php

            namespace Fixtures\Layer\Users\Listeners;

            use Fixtures\Layer\Users\Events\UserInvited;

            class SendInvitation
            {
                public function handle(UserInvited $event): void
                {
                    //
                }
            }
            PHP,
        'Users/Commands/SyncUsersCommand.php' => <<<'PHP'
            <?php

            namespace Fixtures\Layer\Users\Commands;

            use Illuminate\Console\Command;

            class SyncUsersCommand extends Command
            {
                protected $signature = 'fixtures:sync-users';
            }
            PHP,
        'Users/Commands/BaseUsersCommand.php' => <<<'PHP'
            <?php

            namespace Fixtures\Layer\Users\Commands;

            use Illuminate\Console\Command;

            abstract class BaseUsersCommand extends Command
            {
                //
            }
            PHP,
        'Users/Commands/UserCommandOptions.php' => <<<'PHP'
            <?php

            namespace Fixtures\Layer\Users\Commands;

            class UserCommandOptions
            {
                //
            }
            PHP,
    ];

    foreach ($fixtures as $path => $contents) {
        File::ensureDirectoryExists(dirname(base_path("fixtures/layer/{$path}")));
        File::put(base_path("fixtures/layer/{$path}"), $contents);

        require_once base_path("fixtures/layer/{$path}");
    }

    config()->set('ddd.layers', [
        'Layer' => [
            'namespace' => 'Fixtures\Layer',
            'path' => 'fixtures/layer',
        ],
    ]);

    $this->discoveredEvents = function (): array {
        EventServiceProvider::setEventDiscoveryPaths([]);

        (new DddServiceProvider(app()))->register();

        return (new class(app()) extends EventServiceProvider {})->discoverEvents();
    };
});

afterEach(function () {
    EventServiceProvider::setEventDiscoveryPaths([]);

    File::deleteDirectory(base_path('fixtures'));
});

it('discovers the listeners in every domain of a layer', function () {
    expect(($this->discoveredEvents)())
        ->toHaveKey(UserInvited::class)
        ->and(($this->discoveredEvents)()[UserInvited::class])->toContain(SendInvitation::class.'@handle');
});

it('keeps discovering listeners in app/Listeners', function () {
    ($this->discoveredEvents)();

    $paths = (new class(app()) extends EventServiceProvider
    {
        /**
         * @return iterable<int, string>
         */
        public function paths(): iterable
        {
            return $this->discoverEventsWithin();
        }
    })->paths();

    expect(collect($paths)->all())->toContain(app_path('Listeners'));
});

it('does not discover listeners in layers event discovery is disabled for', function () {
    config()->set('ddd.discover_events', false);

    expect(($this->discoveredEvents)())->not->toHaveKey(UserInvited::class);
});

it('discovers the concrete commands in every domain of a layer', function () {
    expect(DddCommandDiscovery::commands())->toBe([SyncUsersCommand::class]);
});

it('does not discover commands when command discovery is disabled', function () {
    config()->set('ddd.discover_commands', false);

    expect(DddCommandDiscovery::commands())->toBe([]);
});

it('only discovers commands in the listed layers', function () {
    config()->set('ddd.discover_commands', 'Domain');

    expect(DddCommandDiscovery::commands())->toBe([]);
});

it('registers the discovered commands with artisan', function () {
    (new DddServiceProvider(app()))->boot();

    expect(array_keys(Artisan::all()))->toContain('fixtures:sync-users');
});
