<?php

declare(strict_types=1);

use Foxws\Ddd\Contracts\Enumerable;

enum Status: string implements Enumerable
{
    case Draft = 'draft';
    case Published = 'published';

    public function label(): string
    {
        return self::labels()[$this->value];
    }

    public static function labels(): array
    {
        return [
            'draft' => 'Draft',
            'published' => 'Published',
        ];
    }
}

it('is a backed enum with labels', function () {
    expect(Status::Published)->toBeInstanceOf(BackedEnum::class)
        ->and(Status::Published->label())->toBe('Published')
        ->and(Status::labels())->toHaveCount(count(Status::cases()));
});
