<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

afterEach(function () {
    File::deleteDirectory(base_path('stubs'));
});

it('publishes the ddd stubs', function () {
    $this->artisan('vendor:publish', ['--tag' => 'ddd-stubs'])->assertSuccessful();

    expect(File::exists(base_path('stubs/action.ddd.stub')))->toBeTrue();
    expect(File::exists(base_path('stubs/helpers.ddd.stub')))->toBeTrue();
});
