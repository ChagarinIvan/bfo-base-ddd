<?php

declare(strict_types=1);

namespace App\Domain\Shared;

use App\Domain\AggregatedRoot;

final readonly class ListingResult
{
    public function __construct(
        public int $total,
        /** @var array<int, AggregatedRoot> $items */
        public array $items = [],
    ) {
    }
}
