<?php

declare(strict_types=1);

namespace App\Domain\ProtocolLine;

use App\Domain\Shared\Criteria;

interface ProtocolLineRepository
{
    public function byId(ProtocolLineId $id): ?ProtocolLine;

    /** @return ProtocolLine[] */
    public function byCriteria(Criteria $criteria): array;
}
