<?php

declare(strict_types=1);

namespace Foxws\Ddd\Concerns;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

/**
 * Keeps the auto-incrementing primary key, and fills a unique "ulid" column that routes
 * bind to, so ids aren't exposed in URLs.
 *
 * @mixin Model
 */
trait HasUlidRouteKey
{
    use HasUlids;

    /**
     * @return list<string>
     */
    public function uniqueIds(): array
    {
        return ['ulid'];
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    public static function findFromUlid(self|string $value): ?static
    {
        if ($value instanceof static) {
            return $value;
        }

        return static::query()->firstWhere('ulid', $value);
    }
}
