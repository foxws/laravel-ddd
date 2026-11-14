<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Workbench\App\Models\Post;

beforeEach(function () {
    Schema::create('posts', function (Blueprint $table) {
        $table->id();
        $table->ulid('ulid')->unique();
        $table->string('title');
        $table->timestamps();
    });
});

it('keeps the incrementing key and fills the ulid column', function () {
    $post = Post::create(['title' => 'Hello']);

    expect($post->getKey())->toBe(1)
        ->and(Str::isUlid($post->ulid))->toBeTrue()
        ->and($post->getRouteKeyName())->toBe('ulid')
        ->and($post->getRouteKey())->toBe($post->ulid);
});

it('finds a model from its ulid', function () {
    $post = Post::create(['title' => 'Hello']);

    expect(Post::findFromUlid($post->ulid)?->is($post))->toBeTrue()
        ->and(Post::findFromUlid($post))->toBe($post)
        ->and(Post::findFromUlid((string) Str::ulid()))->toBeNull();
});
