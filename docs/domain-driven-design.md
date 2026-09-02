# Domain Driven Design

Laravel DDD ships a small set of commands for organizing an application into DDD-style layers instead of the default `app/` structure.

## Layers

Layers are defined in `config('ddd.layers')`. Out of the box you get four:

| Layer | Namespace | Path |
| --- | --- | --- |
| `Domain` | `Domain\` | `src/Domain` |
| `Modules` | `Modules\` | `src/Modules` |
| `Foundation` | `Foundation\` | `src/Foundation` |
| `Support` | `Support\` | `src/Support` |

Publish the config file to add, rename, or remove layers, or point an existing one at `App\` to keep everything under `app/`.

## Installing The Structure

```bash
php artisan ddd:install
```

This registers each layer's namespace in your `composer.json` `autoload.psr-4` map, creates the layer directories, and dumps the autoloader. Run it once, right after installing the package.

```bash
php artisan ddd:install --force            # overwrite namespaces that already point elsewhere
php artisan ddd:install --no-dump-autoload # skip regenerating the autoloader
```

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

Publish a stub to override it for the whole application:

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
    'action' => 'CustomActions',
],
```

## Path Helpers

Two helpers resolve paths the same way the generators do:

```php
domain_path();                  // base_path('src/Domain')
domain_path('Invoice/Actions'); // base_path('src/Domain/Invoice/Actions')

modules_path();                 // base_path('src/Modules')
modules_path('Web/Controllers'); // base_path('src/Modules/Web/Controllers')

layer_path('Support', 'Money'); // base_path('src/Support/Money')
```
