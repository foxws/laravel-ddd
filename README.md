<div align="center">
    <h1>Laravel DDD</h1>
</div>

<p align="center">
    <a href="https://packagist.org/packages/foxws/laravel-ddd"><img src="https://img.shields.io/packagist/v/foxws/laravel-ddd.svg?style=flat-square" alt="Packagist"></a>
    <a href="https://packagist.org/packages/foxws/laravel-ddd"><img src="https://img.shields.io/packagist/php-v/foxws/laravel-ddd.svg?style=flat-square" alt="PHP from Packagist"></a>
    <a href="https://packagist.org/packages/foxws/laravel-ddd"><img src="https://badge.laravel.cloud/badge/foxws/laravel-ddd?style=flat" alt="Laravel versions"></a>
    <a href="https://github.com/foxws/laravel-ddd/actions"><img alt="GitHub Workflow Status (main)" src="https://img.shields.io/github/actions/workflow/status/foxws/laravel-ddd/tests.yml?branch=main&label=Tests&style=flat-square"></a>
    <a href="https://packagist.org/packages/foxws/laravel-ddd"><img src="https://img.shields.io/packagist/dt/foxws/laravel-ddd.svg?style=flat-square" alt="Total Downloads"></a>
</p>

Domain Driven Design scaffolding for Laravel projects

## Installation

You can install the package via Composer:

```bash
composer require foxws/laravel-ddd
```

You may publish the package's config file:

```bash
php artisan vendor:publish --tag="ddd-config"
```

> [!TIP]
> Let your AI agent do the hard parts. This package ships a [Laravel Boost](https://laravel.com/docs/boost) skill, `laravel-ddd-development`, that teaches your agent how to install, configure and use Laravel DDD. Install Boost and select the skill when prompted:
>
> ```bash
> composer require laravel/boost --dev
> php artisan boost:install
> ```
>
> Already using Boost? Run `php artisan boost:update --discover` to pick up the skill. Then just ask, for example: *"Set up Laravel DDD and create a CreateInvoice action in a Billing domain."*

## Usage

Install the DDD layer structure, then generate classes into it:

```bash
php artisan ddd:install
php artisan ddd:make CreateInvoice --type=action --domain=Billing
```

See the [documentation](docs/index.md) for the full list of layers, generator types, and how to customize stubs and subfolders.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Thank you for considering contributing to Laravel DDD! Please review our [contributing guide](.github/CONTRIBUTING.md) to get started.

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [foxws](https://github.com/foxws)
- [All Contributors](../../contributors)

## License

Laravel DDD is open-sourced software licensed under the [MIT license](LICENSE.md).
