---
section: Usage
order: 2
---

# Building Blocks

Small pieces that domain code tends to share, usually from `Domain\Shared`.

## HasUlidRouteKey

`Foxws\Ddd\Concerns\HasUlidRouteKey` keeps a model's auto-incrementing primary key, and fills a unique `ulid` column that route model binding uses, so ids aren't exposed in URLs.

```php
use Foxws\Ddd\Concerns\HasUlidRouteKey;

class Invoice extends Model
{
    use HasUlidRouteKey;
}
```

```php
Schema::create('invoices', function (Blueprint $table) {
    $table->id();
    $table->ulid('ulid')->unique();
    // ...
});
```

`Invoice::findFromUlid($ulid)` finds a model by its ULID, and returns the model as-is when you pass one.

## Enumerable

`Foxws\Ddd\Contracts\Enumerable` is a backed enum with human-readable labels, e.g. to build select options:

```php
use Foxws\Ddd\Contracts\Enumerable;

enum InvoiceStatus: string implements Enumerable
{
    case Draft = 'draft';
    case Paid = 'paid';

    public function label(): string
    {
        return self::labels()[$this->value];
    }

    public static function labels(): array
    {
        return [
            'draft' => __('Draft'),
            'paid' => __('Paid'),
        ];
    }
}
```
