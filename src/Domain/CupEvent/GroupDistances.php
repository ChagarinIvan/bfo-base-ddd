<?php

declare(strict_types=1);

namespace App\Domain\CupEvent;

use App\Domain\Distance\DistanceId;

final readonly class GroupDistances
{
    public function __construct(
        public string $groupId,
        /** @var DistanceId[] */
        public array $distances,
    ) {
    }
}
