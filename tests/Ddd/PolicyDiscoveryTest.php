<?php

declare(strict_types=1);

use Fixtures\App\Models\User;
use Fixtures\Domain\Billing\Models\Invoice;
use Fixtures\Domain\Billing\Permissions\InvoicePolicy as SubstitutedInvoicePolicy;
use Fixtures\Domain\Billing\Policies\InvoicePolicy;
use Fixtures\Domain\Users\Policies\UserPolicy;
use Fixtures\Models\Post;
use Fixtures\Policies\PostPolicy;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;

beforeEach(function () {
    $fixtures = [
        'App/Models/User.php' => 'namespace Fixtures\App\Models; class User {}',
        'Models/Post.php' => 'namespace Fixtures\Models; class Post {}',
        'Policies/PostPolicy.php' => 'namespace Fixtures\Policies; class PostPolicy {}',
        'Domain/Billing/Models/Invoice.php' => 'namespace Fixtures\Domain\Billing\Models; class Invoice {}',
        'Domain/Billing/Policies/InvoicePolicy.php' => 'namespace Fixtures\Domain\Billing\Policies; class InvoicePolicy {}',
        'Domain/Billing/Permissions/InvoicePolicy.php' => 'namespace Fixtures\Domain\Billing\Permissions; class InvoicePolicy {}',
        'Domain/Users/Policies/UserPolicy.php' => 'namespace Fixtures\Domain\Users\Policies; class UserPolicy {}',
    ];

    foreach ($fixtures as $path => $code) {
        File::ensureDirectoryExists(dirname(base_path("fixtures/{$path}")));
        File::put(base_path("fixtures/{$path}"), "<?php\n\n{$code}\n");

        require_once base_path("fixtures/{$path}");
    }

    config()->set('ddd.layers.Domain.namespace', 'Fixtures\Domain');
    config()->set('ddd.layers.Domain.path', 'fixtures/Domain');
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
