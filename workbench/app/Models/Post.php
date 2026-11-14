<?php

declare(strict_types=1);

namespace Workbench\App\Models;

use Foxws\Ddd\Concerns\HasUlidRouteKey;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasUlidRouteKey;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
    ];
}
