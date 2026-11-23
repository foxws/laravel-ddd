# Release Notes

## [Unreleased](https://github.com/foxws/laravel-ddd/compare/2.0.1...HEAD)

Split out of [foxws/laravel-essentials](https://github.com/foxws/laravel-essentials): the `ddd:install`/`ddd:make*` commands, layer configuration, and stubs now live here.

## [2.0.1](https://github.com/foxws/laravel-ddd/compare/2.0.0...2.0.1) - 2026-11-23

- A model's own policy now comes before the pluralized guess, and the pluralized guess is no longer made for classes of installed packages.

**Upgrading:** a model that relied on another domain's policy through the pluralized guess now has none, so its checks are denied. Register that policy with `Gate::policy()`.

<!-- Release notes generated using configuration in .github/release.yml at 83f434d097df9e37e76b4474d99a836034b9a3ab -->
### What's Changed

#### Other Changes

* Harden the workflows by @francoism90 in https://github.com/foxws/laravel-ddd/pull/17
* Prefer a model's own policy over the pluralized guess by @francoism90 in https://github.com/foxws/laravel-ddd/pull/18

**Full Changelog**: https://github.com/foxws/laravel-ddd/compare/2.0.0...2.0.1

## [2.0.0](https://github.com/foxws/laravel-ddd/compare/1.1.0...2.0.0) - 2026-11-19

This release requires PHP 8.5 or newer. PHP 8.4 is no longer supported, and PHP 8.6 is now tested in CI. Stay on the previous version if you still run PHP 8.4.

<!-- Release notes generated using configuration in .github/release.yml at d0c8eda1161e6969b4315946e9798708d542141f -->
### What's Changed

#### Other Changes

* Drop PHP 8.4 and add PHP 8.6 by @francoism90 in https://github.com/foxws/laravel-ddd/pull/16

**Full Changelog**: https://github.com/foxws/laravel-ddd/compare/1.1.0...2.0.0

## [1.1.0](https://github.com/foxws/laravel-ddd/compare/1.0.0...1.1.0) - 2026-11-14

<!-- Release notes generated using configuration in .github/release.yml at e327cff705a86a27953920c5522f6b67f05ec074 -->
### What's Changed

#### Other Changes

* Add an enumerable stub for foxws/laravel-essentials enums by @francoism90 in https://github.com/foxws/laravel-ddd/pull/15

**Full Changelog**: https://github.com/foxws/laravel-ddd/compare/1.0.0...1.1.0

## [1.0.0](https://github.com/foxws/laravel-ddd/compare/0.0.9...1.0.0) - 2026-11-12

<!-- Release notes generated using configuration in .github/release.yml at main -->
### What's Changed

#### Other Changes

* Match a listener's layer by directory, not by path prefix by @francoism90 in https://github.com/foxws/laravel-ddd/pull/12
* Discover the listeners and console commands in every domain by @francoism90 in https://github.com/foxws/laravel-ddd/pull/13
* Bump shivammathur/setup-php from 2.37.2 to 2.40.0 by @dependabot[bot] in https://github.com/foxws/laravel-ddd/pull/10

### New Contributors

* @dependabot[bot] made their first contribution in https://github.com/foxws/laravel-ddd/pull/10

**Full Changelog**: https://github.com/foxws/laravel-ddd/compare/0.0.9...1.0.0

## [0.0.9](https://github.com/foxws/laravel-ddd/compare/0.0.8...0.0.9) - 2026-10-14

<!-- Release notes generated using configuration in .github/release.yml at main -->
### What's Changed

#### Other Changes

* Add Laravel Boost tip to README and docs by @francoism90 in https://github.com/foxws/laravel-ddd/pull/9

**Full Changelog**: https://github.com/foxws/laravel-ddd/compare/0.0.8...0.0.9

## [0.0.8](https://github.com/foxws/laravel-ddd/compare/0.0.7...0.0.8) - 2026-10-13

<!-- Release notes generated using configuration in .github/release.yml at main -->
### What's Changed

#### Other Changes

* Generate channels into Broadcasting to match make:channel by @francoism90 in https://github.com/foxws/laravel-ddd/pull/8

**Full Changelog**: https://github.com/foxws/laravel-ddd/compare/0.0.7...0.0.8

## [0.0.7](https://github.com/foxws/laravel-ddd/compare/0.0.6...0.0.7) - 2026-10-13

<!-- Release notes generated using configuration in .github/release.yml at a552de37b8f868ebfda0de08dd03ba8a02ed866c -->
### What's Changed

#### Other Changes

* Add discover_policies config for layer-aware policy discovery by @francoism90 in https://github.com/foxws/laravel-ddd/pull/7

**Full Changelog**: https://github.com/foxws/laravel-ddd/compare/0.0.6...0.0.7

## [0.0.6](https://github.com/foxws/laravel-ddd/compare/0.0.5...0.0.6) - 2026-10-02

<!-- Release notes generated using configuration in .github/release.yml at main -->
### What's Changed

#### Other Changes

* docs: add the foxws.nl homepage group and a hero lead by @francoism90 in https://github.com/foxws/laravel-ddd/pull/5
* Raise PHPStan to level 8 by @francoism90 in https://github.com/foxws/laravel-ddd/pull/6

**Full Changelog**: https://github.com/foxws/laravel-ddd/compare/0.0.5...0.0.6

## [0.0.5](https://github.com/foxws/laravel-ddd/compare/0.0.4...0.0.5) - 2026-09-17

<!-- Release notes generated using configuration in .github/release.yml at main -->
### What's Changed

#### Other Changes

* Docs/laravel docs metadata by @francoism90 in https://github.com/foxws/laravel-ddd/pull/4

**Full Changelog**: https://github.com/foxws/laravel-ddd/compare/0.0.4...0.0.5

## [0.0.4](https://github.com/foxws/laravel-ddd/compare/0.0.3...0.0.4) - 2026-09-15

<!-- Release notes generated using configuration in .github/release.yml at main -->
### What's Changed

#### Other Changes

* docs: complete bundled Boost skill for laravel-ddd by @francoism90 in https://github.com/foxws/laravel-ddd/pull/3

**Full Changelog**: https://github.com/foxws/laravel-ddd/compare/0.0.3...0.0.4

## [0.0.3](https://github.com/foxws/laravel-ddd/compare/0.0.2...0.0.3) - 2026-09-12

<!-- Release notes generated using configuration in .github/release.yml at main -->
### What's Changed

#### Other Changes

* Enable Infrastructure/Integrations layers by default, add matching path helpers by @francoism90 in https://github.com/foxws/laravel-ddd/pull/2

**Full Changelog**: https://github.com/foxws/laravel-ddd/compare/0.0.2...0.0.3

## [0.0.2](https://github.com/foxws/laravel-ddd/compare/0.0.1...0.0.2) - 2026-09-12

<!-- Release notes generated using configuration in .github/release.yml at main -->
### What's Changed

#### Other Changes

* Add discover_events config for layer-aware event listener discovery by @francoism90 in https://github.com/foxws/laravel-ddd/pull/1

### New Contributors

* @francoism90 made their first contribution in https://github.com/foxws/laravel-ddd/pull/1

**Full Changelog**: https://github.com/foxws/laravel-ddd/compare/0.0.1...0.0.2

## [0.0.1](https://github.com/foxws/laravel-ddd/compare/v0.1.0...0.0.1) - 2026-09-03

<!-- Release notes generated using configuration in .github/release.yml at main -->
**Full Changelog**: https://github.com/foxws/laravel-ddd/commits/0.0.1

## [v0.1.0](https://github.com/foxws/laravel-ddd/compare/...v0.1.0) - 202x-xx-xx

Initial pre-release.
