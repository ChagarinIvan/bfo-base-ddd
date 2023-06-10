<?php

declare(strict_types=1);

namespace App\Domain\Shared;

interface Collection
{
    public function first(callable $callback): mixed;
}
