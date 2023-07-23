<?php

declare(strict_types=1);

namespace App\Domain\CupEvent;

final readonly class CupEventAttributes
{
    public function __construct(
        public GroupsDistances $groupsDistances,
        public float $points,
    ) {
    }
}
