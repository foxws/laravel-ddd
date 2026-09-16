---
section: Reference
order: 1
---

# Configuration Reference

Publish the config file to customize any of these options:

```bash
php artisan vendor:publish --tag="ddd-config"
```

| Key | Env | Default | Description |
| --- | --- | --- | --- |
| `substitutions` | `DDD_SUBSTITUTIONS` | `[]` | Overrides the type → subfolder mapping used by `ddd:make`, e.g. pointing `action` at a different folder than `Actions`. |
| `stubs` | `DDD_STUBS` | `[]` | Maps a type to a stub file, when it shouldn't come from `stubs/{type}.ddd.stub` or the package's bundled stub. |
| `discover_events` | `DDD_DISCOVER_EVENTS` | `true` | When enabled, Laravel's event listener discovery resolves class names using each layer's namespace and path, not just `app/`. |
| `layers` | — | `Domain`, `Modules`, `Foundation`, `Support`, `Infrastructure`, `Integrations` | The DDD layers. Each layer has its own `namespace` and `path`. |

## Layers

`layers` isn't a single value — it's a list of layers, and each one has its own namespace and path, each configurable through its own env variable:

| Layer | Namespace env | Path env | Default namespace | Default path |
| --- | --- | --- | --- | --- |
| `Domain` | `DDD_DOMAIN_NAMESPACE` | `DDD_DOMAIN_PATH` | `Domain` | `src/Domain` |
| `Modules` | `DDD_MODULES_NAMESPACE` | `DDD_MODULES_PATH` | `Modules` | `src/Modules` |
| `Foundation` | `DDD_FOUNDATION_NAMESPACE` | `DDD_FOUNDATION_PATH` | `Foundation` | `src/Foundation` |
| `Support` | `DDD_SUPPORT_NAMESPACE` | `DDD_SUPPORT_PATH` | `Support` | `src/Support` |
| `Infrastructure` | `DDD_INFRASTRUCTURE_NAMESPACE` | `DDD_INFRASTRUCTURE_PATH` | `Infrastructure` | `src/Infrastructure` |
| `Integrations` | `DDD_INTEGRATIONS_NAMESPACE` | `DDD_INTEGRATIONS_PATH` | `Integrations` | `src/Integrations` |

To add, rename, or remove a layer, publish the config file and edit the `layers` array directly.

See [Domain Driven Design](domain-driven-design.md) for how `layers`, `substitutions`, and `stubs` are used.
