<?php

declare(strict_types=1);

use Fixtures\Domain\Events\UserRegistered;
use Fixtures\Domain\Listeners\SendWelcomeEmail;
use Illuminate\Foundation\Events\DiscoverEvents;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    $this->layerPath = base_path('fixtures/domain');

    File::ensureDirectoryExists($this->layerPath.'/Events');
    File::ensureDirectoryExists($this->layerPath.'/Listeners');

    File::put($this->layerPath.'/Events/UserRegistered.php', <<<'PHP'
        <?php

        namespace Fixtures\Domain\Events;

        class UserRegistered
        {
            //
        }
        PHP
    );

    File::put($this->layerPath.'/Listeners/SendWelcomeEmail.php', <<<'PHP'
        <?php

        namespace Fixtures\Domain\Listeners;

        use Fixtures\Domain\Events\UserRegistered;

        class SendWelcomeEmail
        {
            public function handle(UserRegistered $event): void
            {
                //
            }
        }
        PHP
    );

    require_once $this->layerPath.'/Events/UserRegistered.php';
    require_once $this->layerPath.'/Listeners/SendWelcomeEmail.php';

    config()->set('ddd.layers.Domain.namespace', 'Fixtures\Domain');
    config()->set('ddd.layers.Domain.path', 'fixtures/domain');
});

afterEach(function () {
    File::deleteDirectory(base_path('fixtures'));
});

it('discovers listeners using the layer namespace and path', function () {
    $events = DiscoverEvents::within($this->layerPath.'/Listeners', app_path());

    expect($events)->toHaveKey(UserRegistered::class);
    expect($events[UserRegistered::class])
        ->toContain(SendWelcomeEmail::class.'@handle');
});

it('falls back to the default guesser when disabled', function () {
    config()->set('ddd.discover_events', false);

    $events = DiscoverEvents::within($this->layerPath.'/Listeners', app_path());

    expect($events)->not->toHaveKey(UserRegistered::class);
});
