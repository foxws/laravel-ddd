<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

afterEach(function () {
    foreach (['src/Domain', 'src/Modules', 'src/Foundation', 'src/Support'] as $path) {
        File::deleteDirectory(base_path($path));
    }
});

it('creates into the domain layer via ddd:make-domain', function () {
    $this->artisan('ddd:make-domain', ['name' => 'Post', '--type' => 'model', '--domain' => 'Posts'])
        ->assertSuccessful();

    expect(File::exists(base_path('src/Domain/Posts/Models/Post.php')))->toBeTrue();
});

it('creates into the modules layer via ddd:make-module', function () {
    $this->artisan('ddd:make-module', ['name' => 'Post', '--type' => 'model', '--domain' => 'Posts'])
        ->assertSuccessful();

    expect(File::exists(base_path('src/Modules/Posts/Models/Post.php')))->toBeTrue();
});

it('creates into the foundation layer via ddd:make-foundation', function () {
    $this->artisan('ddd:make-foundation', ['name' => 'Post', '--type' => 'model', '--domain' => 'Posts'])
        ->assertSuccessful();

    expect(File::exists(base_path('src/Foundation/Posts/Models/Post.php')))->toBeTrue();
});

it('creates into the support layer via ddd:make-support', function () {
    $this->artisan('ddd:make-support', ['name' => 'Post', '--type' => 'model', '--domain' => 'Posts'])
        ->assertSuccessful();

    expect(File::exists(base_path('src/Support/Posts/Models/Post.php')))->toBeTrue();
});
