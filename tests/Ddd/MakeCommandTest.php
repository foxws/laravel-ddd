<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

afterEach(function () {
    File::deleteDirectory(base_path('src/Domain'));
    File::deleteDirectory(base_path('src/Modules'));
    File::deleteDirectory(base_path('stubs/ddd'));
});

it('creates a model with an explicit domain', function () {
    $this->artisan('ddd:make', ['name' => 'Post', '--type' => 'model', '--domain' => 'Posts'])
        ->assertSuccessful();

    $path = base_path('src/Domain/Posts/Models/Post.php');

    expect(File::exists($path))->toBeTrue();
    expect(File::get($path))->toContain('namespace Domain\Posts\Models;');
});

it('guesses the domain from the last word of the name when --domain is omitted', function () {
    $this->artisan('ddd:make', ['name' => 'CreateNewPost', '--type' => 'action'])
        ->assertSuccessful();

    $path = base_path('src/Domain/Post/Actions/CreateNewPost.php');

    expect(File::exists($path))->toBeTrue();
    expect(File::get($path))->toContain('namespace Domain\Post\Actions;');
});

it('generates into a different layer via --layer', function () {
    $this->artisan('ddd:make', ['name' => 'Post', '--type' => 'model', '--domain' => 'Posts', '--layer' => 'Modules'])
        ->assertSuccessful();

    expect(File::exists(base_path('src/Modules/Posts/Models/Post.php')))->toBeTrue();
});

it('fails for an unknown type', function () {
    $this->artisan('ddd:make', ['name' => 'Post', '--type' => 'bogus', '--domain' => 'Posts'])
        ->expectsOutputToContain('Unknown type "bogus".')
        ->assertFailed();
});

it('fails when --type is omitted', function () {
    $this->artisan('ddd:make', ['name' => 'Post', '--domain' => 'Posts'])
        ->expectsOutputToContain('The --type option is required')
        ->assertFailed();
});

it('fails when no stub exists for the type', function () {
    config(['ddd.substitutions' => ['widget' => 'Widgets']]);

    $this->artisan('ddd:make', ['name' => 'PostWidget', '--type' => 'widget', '--domain' => 'Posts'])
        ->assertFailed();
});

it('resolves a stub override from ddd.stubs', function () {
    File::ensureDirectoryExists(base_path('stubs/ddd'));
    File::put(base_path('stubs/ddd/custom-widget.stub'), "<?php\n\nnamespace {{ namespace }};\n\nfinal class {{ class }}\n{\n    public const CUSTOM = true;\n}\n");

    config([
        'ddd.substitutions' => ['widget' => 'Widgets'],
        'ddd.stubs' => ['widget' => 'stubs/ddd/custom-widget.stub'],
    ]);

    $this->artisan('ddd:make', ['name' => 'PostWidget', '--type' => 'widget', '--domain' => 'Posts'])
        ->assertSuccessful();

    $path = base_path('src/Domain/Posts/Widgets/PostWidget.php');

    expect(File::exists($path))->toBeTrue();
    expect(File::get($path))->toContain('CUSTOM = true');
});

it('has a stub for every built-in type', function (string $type) {
    $this->artisan('ddd:make', ['name' => 'Example', '--type' => $type, '--domain' => 'Examples'])
        ->assertSuccessful();
})->with([
    'action', 'cast', 'channel', 'class', 'collection', 'command', 'contract', 'controller',
    'data', 'dto', 'enum', 'event', 'exception', 'factory', 'filter', 'job', 'listener', 'mail',
    'middleware', 'migration', 'model', 'notification', 'observer', 'pipe', 'policy', 'provider',
    'query_builder', 'request', 'resource', 'rule', 'scope', 'seeder', 'service', 'setting',
    'state', 'trait', 'value_object', 'view_model',
]);

it('nests under an extra namespace segment when the substitution contains one', function () {
    config(['ddd.substitutions' => ['query_builder' => 'Eloquent\Builders']]);

    $this->artisan('ddd:make', ['name' => 'PostQueryBuilder', '--type' => 'query_builder', '--domain' => 'Posts'])
        ->assertSuccessful();

    $path = base_path('src/Domain/Posts/Eloquent/Builders/PostQueryBuilder.php');

    expect(File::exists($path))->toBeTrue();
    expect(File::get($path))->toContain('namespace Domain\Posts\Eloquent\Builders;');
});

it('fails when the target layer is disabled', function () {
    config(['ddd.layers.Domain' => null]);

    $this->artisan('ddd:make', ['name' => 'Post', '--type' => 'model', '--domain' => 'Posts'])
        ->expectsOutputToContain('The "Domain" layer is not configured or has been disabled in ddd.layers.')
        ->assertFailed();
});
