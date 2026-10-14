---
title: Introduction
metadata:
  role: Architecture
  group: foundations
  eyebrow: "DDD · Scaffolding · Artisan"
  desc: "Organize your Laravel app into Domain-Driven Design layers."
  requires: "PHP ^8.4"
  laravel: "13.x"
  licence: MIT
---

# Introduction

Laravel DDD helps you organize a Laravel app using Domain-Driven Design (DDD) layers, instead of the framework's default `app/` folder.

- `ddd:install` sets up the layer folders and registers their namespaces in your `composer.json`.
- `ddd:make` generates new classes straight into the right layer.

## Installation

Install the package with Composer:

```bash
composer require foxws/laravel-ddd
```

You can publish the config file if you want to customize it:

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

## Learn more

- [Domain Driven Design](domain-driven-design.md) — the layers, `ddd:install`, and `ddd:make`.
- [Configuration Reference](configuration.md) — every `config/ddd.php` option.

Looking for Laravel Essentials' sensible defaults (`Model::shouldBeStrict()`, `URL::forceHttps()`, and more)? Those live in a separate package, [foxws/laravel-essentials](https://github.com/foxws/laravel-essentials).
