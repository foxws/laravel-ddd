# Configuration Reference

Publish the config file to customize any of these options:

```bash
php artisan vendor:publish --tag="ddd-config"
```

| Key | Env | Default | Description |
| --- | --- | --- | --- |
| `substitutions` | `DDD_SUBSTITUTIONS` | `[]` | Overrides for the `--type` → subfolder mapping used by `ddd:make`. |
| `stubs` | `DDD_STUBS` | `[]` | `type => stub path` overrides for `ddd:make`. |
| `layers` | — | `Domain`, `Modules`, `Foundation`, `Support` | DDD layers, each with a `namespace` and `path`. |

See [Domain Driven Design](domain-driven-design.md) for how `layers`, `substitutions`, and `stubs` are used.
