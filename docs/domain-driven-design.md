# Domain Driven Design

Laravel DDD ships a small set of commands for organizing an application into DDD-style layers instead of the default `app/` structure.

## Layers

Layers are defined in `config('ddd.layers')`. Out of the box you get six:

| Layer | Namespace | Path |
| --- | --- | --- |
| `Domain` | `Domain\` | `src/Domain` |
| `Modules` | `Modules\` | `src/Modules` |
| `Foundation` | `Foundation\` | `src/Foundation` |
| `Support` | `Support\` | `src/Support` |
| `Infrastructure` | `Infrastructure\` | `src/Infrastructure` |
| `Integrations` | `Integrations\` | `src/Integrations` |

Publish the config file to add, rename, or remove layers, or point an existing one at `App\` to keep everything under `app/`.

`Infrastructure` holds concrete adapters to external systems (repositories, storage, queues); `Integrations` holds third-party service integrations (payments, notifications, etc.). Remove either from the published config if your application doesn't need the distinction.

## Installing The Structure

```bash
php artisan ddd:install
```

This registers each layer's namespace in your `composer.json` `autoload.psr-4` map, creates the layer directories, and dumps the autoloader. Run it once, right after installing the package.

```bash
php artisan ddd:install --force            # overwrite namespaces that already point elsewhere
php artisan ddd:install --no-dump-autoload # skip regenerating the autoloader
```

### Manual Install

`ddd:install` is a convenience wrapper around a few file edits, so you can skip it and reproduce the same outcome by hand. Add the default layers, plus `Database\Factories\` and `Database\Seeders\`, to `autoload.psr-4`, and the `Foundation` layer's `Helpers.php` to `autoload.files`:

```json
{
    "autoload": {
        "psr-4": {
            "Domain\\": "src/Domain/",
            "Modules\\": "src/Modules/",
            "Foundation\\": "src/Foundation/",
            "Support\\": "src/Support/",
            "Infrastructure\\": "src/Infrastructure/",
            "Integrations\\": "src/Integrations/",
            "Database\\Factories\\": "database/factories/",
            "Database\\Seeders\\": "database/seeders/"
        },
        "files": [
            "src/Foundation/Helpers.php"
        ]
    }
}
```

If you removed the `Infrastructure` or `Integrations` layers from `config/ddd.php`, drop them from `autoload.psr-4` the same way.

Then create the layer directories and `src/Foundation/Helpers.php` from the package's `helpers.ddd.stub` (see [Customizing Stubs](#customizing-stubs) to change its default content), and run `composer dump-autoload` to pick up the new mappings.

## Generating Classes

`ddd:make` generates a class into a layer, in the style of `make:model` and friends:

```bash
php artisan ddd:make CreateInvoice --type=action
# Domain\Invoice\Actions\CreateInvoice
```

The domain is guessed from the class name (`Invoice` here) unless you pass `--domain`:

```bash
php artisan ddd:make Actions/CreateInvoice --type=action --domain=Billing
```

Layer-specific shortcuts skip the `--layer` option:

```bash
php artisan ddd:make-domain Invoice --type=model
php artisan ddd:make-module InvoiceController --type=controller
php artisan ddd:make-foundation AppServiceProvider --type=provider
php artisan ddd:make-support Money --type=value_object
```

### Available Types

`--type` selects both the stub and the subfolder the class is generated into:

```text
action        cast          channel       class         collection
command       contract      controller    data          dto
enum          event         exception     factory       filter
job           listener      mail          middleware    migration
model         notification  observer      pipe          policy
provider      query_builder request       resource      rule
scope         seeder        service       setting       state
trait         value_object  view_model
```

## Customizing Stubs

Publish the stubs to override them for the whole application:

```bash
php artisan vendor:publish --tag=ddd-stubs
```

This copies every `*.ddd.stub` file into `stubs/`; delete the ones you don't want to override. To publish a single stub instead:

```bash
mkdir -p stubs && cp vendor/foxws/laravel-ddd/stubs/action.ddd.stub stubs/action.ddd.stub
```

Or point a type at any file via config:

```php
// config/ddd.php
'stubs' => [
    'action' => 'stubs/ddd/custom-action.stub',
],
```

## Customizing Subfolders

Each type maps to a subfolder under the domain (`action` → `Actions`, `model` → `Models`, and so on). Override or add entries via config:

```php
// config/ddd.php
'substitutions' => [
    'action' => 'UseCases',                  // Domain\Invoice\Actions\... becomes Domain\Invoice\UseCases\...
    'query_builder' => 'Eloquent\Builders',   // nest under an extra namespace segment
],
```

## Path Helpers

A helper resolves the path for each default layer, the same way the generators do:

```php
domain_path();                  // base_path('src/Domain')
domain_path('Invoice/Actions'); // base_path('src/Domain/Invoice/Actions')

modules_path();                 // base_path('src/Modules')
modules_path('Web/Controllers'); // base_path('src/Modules/Web/Controllers')

foundation_path();              // base_path('src/Foundation')
foundation_path('Providers');   // base_path('src/Foundation/Providers')

support_path();                 // base_path('src/Support')
support_path('Money');          // base_path('src/Support/Money')

infrastructure_path();          // base_path('src/Infrastructure')
infrastructure_path('Storage'); // base_path('src/Infrastructure/Storage')

integrations_path();            // base_path('src/Integrations')
integrations_path('Stripe');    // base_path('src/Integrations/Stripe')
```

For a custom or renamed layer, use `layer_path()` directly:

```php
layer_path('Billing', 'Invoices'); // base_path('src/Billing/Invoices')
```
