<?php

declare(strict_types=1);

namespace App\Domain\Shared;

use function array_filter;

final class Collection
{
    public static function empty(): self
    {
        return new self();
    }

    public function first(callable $callback): mixed
    {
        return array_filter($this->items, $callback)[0] ?? null;
    }

    private function __construct(
        /** @var array<int, mixed> $items */
        private array $items = [],
    ) {
    }
}
