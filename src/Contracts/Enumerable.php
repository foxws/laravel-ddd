<?php

declare(strict_types=1);

namespace Foxws\Ddd\Contracts;

use BackedEnum;

/**
 * A backed enum with human-readable labels, e.g. for select options.
 */
interface Enumerable extends BackedEnum
{
    public function label(): string;

    /**
     * The labels of every case, keyed by value.
     *
     * @return array<int|string, string>
     */
    public static function labels(): array;
}
