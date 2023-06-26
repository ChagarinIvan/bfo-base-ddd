<?php

declare(strict_types=1);

namespace App\Domain\Distance;

use App\Domain\Shared\Criteria;

interface DistanceRepository
{
    public function byId(DistanceId $id): ?Distance;

    /** @return Distance[] */
    public function byCriteria(Criteria $criteria): array;
}
