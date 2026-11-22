<?php

declare(strict_types=1);

use Fixtures\App\Models\User;
use Fixtures\Domain\Billing\Models\Invoice;
use Fixtures\Domain\Billing\Permissions\InvoicePolicy as SubstitutedInvoicePolicy;
use Fixtures\Domain\Billing\Policies\InvoicePolicy;
use Fixtures\Domain\Users\Policies\UserPolicy;
use Fixtures\Models\Post;
use Fixtures\Modules\Users\Policies\UserPolicy as ModulesUserPolicy;
use Fixtures\Policies\PostPolicy;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Stringable;

beforeEach(function () {
    $fixtures = [
        'App/Models/User.php' => 'namespace Fixtures\App\Models; class User {}',
        'Models/Post.php' => 'namespace Fixtures\Models; class Post {}',
        'Policies/PostPolicy.php' => 'namespace Fixtures\Policies; class PostPolicy {}',
        'Domain/Billing/Models/Invoice.php' => 'namespace Fixtures\Domain\Billing\Models; class Invoice {}',
        'Domain/Billing/Policies/InvoicePolicy.php' => 'namespace Fixtures\Domain\Billing\Policies; class InvoicePolicy {}',
        'Domain/Billing/Permissions/InvoicePolicy.php' => 'namespace Fixtures\Domain\Billing\Permissions; class InvoicePolicy {}',
        'Domain/Users/Policies/UserPolicy.php' => 'namespace Fixtures\Domain\Users\Policies; class UserPolicy {}',
        'Modules/Users/Policies/UserPolicy.php' => 'namespace Fixtures\Modules\Users\Policies; class UserPolicy {}',
    ];

    foreach ($fixtures as $path => $code) {
        File::ensureDirectoryExists(dirname(base_path("fixtures/{$path}")));
        File::put(base_path("fixtures/{$path}"), "<?php\n\n{$code}\n");

        require_once base_path("fixtures/{$path}");
    }

    config()->set('ddd.layers.Domain.namespace', 'Fixtures\Domain');
    config()->set('ddd.layers.Domain.path', 'fixtures/Domain');
    config()->set('ddd.layers.Modules.namespace', 'Fixtures\Modules');
    config()->set('ddd.layers.Modules.path', 'fixtures/Modules');
});

afterEach(function () {
    File::deleteDirectory(base_path('fixtures'));
});

it('discovers policies next to a layer model', function () {
    expect(Gate::getPolicyFor(Invoice::class))->toBeInstanceOf(InvoicePolicy::class);
});

it('discovers policies in the pluralized domain of the model', function () {
    expect(Gate::getPolicyFor(User::class))->toBeInstanceOf(UserPolicy::class);
});

it('uses the policy substitution for the policies folder', function () {
    config()->set('ddd.substitutions', ['policy' => 'Permissions']);

    expect(Gate::getPolicyFor(Invoice::class))->toBeInstanceOf(SubstitutedInvoicePolicy::class);
});

it('falls back to the default policy guesses', function () {
    expect(Gate::getPolicyFor(Post::class))->toBeInstanceOf(PostPolicy::class);
});

it('only uses the default policy guesses when disabled', function () {
    config()->set('ddd.discover_policies', false);

    expect(Gate::getPolicyFor(User::class))->toBeNull();
    expect(Gate::getPolicyFor(Post::class))->toBeInstanceOf(PostPolicy::class);
});

it('discovers pluralized policies in the listed layers', function () {
    config()->set('ddd.discover_policies', ['Modules']);

    expect(Gate::getPolicyFor(User::class))->toBeInstanceOf(ModulesUserPolicy::class);
});

it('prefers the policy of the model over the pluralized domain', function () {
    defineFixture('App/Models/Order.php', 'namespace Fixtures\App\Models; class Order {}');
    defineFixture('App/Policies/OrderPolicy.php', 'namespace Fixtures\App\Policies; class OrderPolicy {}');
    defineFixture('Domain/Orders/Policies/OrderPolicy.php', 'namespace Fixtures\Domain\Orders\Policies; class OrderPolicy {}');

    expect(Gate::getPolicyFor('Fixtures\App\Models\Order'))->toBeInstanceOf('Fixtures\App\Policies\OrderPolicy');
});

it('does not use the policy of another domain', function (string $path, string $namespace) {
    defineFixture($path, "namespace {$namespace}; class User {}");

    expect(Gate::getPolicyFor("{$namespace}\\User"))->toBeNull();
})->with([
    'model' => ['Domain/Admin/Models/User.php', 'Fixtures\Domain\Admin\Models'],
    'class outside a Models folder' => ['Domain/Staff/User.php', 'Fixtures\Domain\Staff'],
    'model in another layer' => ['Modules/Admin/Models/User.php', 'Fixtures\Modules\Admin\Models'],
]);

it('uses the policy of the same domain in another layer', function () {
    defineFixture('Modules/Teams/Models/Team.php', 'namespace Fixtures\Modules\Teams\Models; class Team {}');
    defineFixture('Domain/Teams/Policies/TeamPolicy.php', 'namespace Fixtures\Domain\Teams\Policies; class TeamPolicy {}');

    expect(Gate::getPolicyFor('Fixtures\Modules\Teams\Models\Team'))->toBeInstanceOf('Fixtures\Domain\Teams\Policies\TeamPolicy');
});

it('discovers the pluralized policy of a model at the root of a layer', function () {
    defineFixture('Domain/Ticket.php', 'namespace Fixtures\Domain; class Ticket {}');
    defineFixture('Domain/Tickets/Policies/TicketPolicy.php', 'namespace Fixtures\Domain\Tickets\Policies; class TicketPolicy {}');

    expect(Gate::getPolicyFor('Fixtures\Domain\Ticket'))->toBeInstanceOf('Fixtures\Domain\Tickets\Policies\TicketPolicy');
});

it('does not guess a domain policy for classes of installed packages', function () {
    defineFixture('Domain/Stringables/Policies/StringablePolicy.php', 'namespace Fixtures\Domain\Stringables\Policies; class StringablePolicy {}');

    expect(Gate::getPolicyFor(Stringable::class))->toBeNull();
});

function defineFixture(string $path, string $code): void
{
    File::ensureDirectoryExists(dirname(base_path("fixtures/{$path}")));
    File::put(base_path("fixtures/{$path}"), "<?php\n\n{$code}\n");

    require_once base_path("fixtures/{$path}");
}
