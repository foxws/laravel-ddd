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

it('resolves the foundation path', function () {
    expect(foundation_path())->toBe(base_path('src/Foundation'));
    expect(foundation_path('Providers'))->toBe(base_path('src/Foundation/Providers'));
});

it('resolves the support path', function () {
    expect(support_path())->toBe(base_path('src/Support'));
    expect(support_path('Money'))->toBe(base_path('src/Support/Money'));
});

it('resolves the infrastructure path', function () {
    expect(infrastructure_path())->toBe(base_path('src/Infrastructure'));
    expect(infrastructure_path('Storage'))->toBe(base_path('src/Infrastructure/Storage'));
});

it('resolves the integrations path', function () {
    expect(integrations_path())->toBe(base_path('src/Integrations'));
    expect(integrations_path('Stripe'))->toBe(base_path('src/Integrations/Stripe'));
});
