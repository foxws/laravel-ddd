<?php

declare(strict_types=1);

it('resolves a layer path from config', function () {
    expect(layer_path('Domain'))->toBe(base_path('src/Domain'));
    expect(layer_path('Domain', 'Posts/Models'))->toBe(base_path('src/Domain/Posts/Models'));
});

it('resolves the domain path', function () {
    expect(domain_path())->toBe(base_path('src/Domain'));
    expect(domain_path('Posts/Actions'))->toBe(base_path('src/Domain/Posts/Actions'));
});

it('resolves the modules path', function () {
    expect(modules_path())->toBe(base_path('src/Modules'));
    expect(modules_path('Web/Http/Controllers'))->toBe(base_path('src/Modules/Web/Http/Controllers'));
});
