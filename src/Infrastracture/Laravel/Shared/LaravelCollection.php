<?php

declare(strict_types=1);

namespace App\Infrastracture\Laravel\Shared;

use App\Domain\Shared\Collection;
use Illuminate\Support\Collection as BaseCollection;

final class LaravelCollection implements Collection
{
    public function __construct(private BaseCollection $collection)
    {
    }

    public function first(callable $callback): mixed
    {
        $this->collection->first();
    }
}
