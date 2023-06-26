<?php

declare(strict_types=1);

namespace App\Domain\ProtocolLine;

use App\Domain\AggregatedRoot;
use App\Domain\Club\ClubId;
use App\Domain\Distance\DistanceId;
use App\Domain\Person\PersonId;
use App\Domain\Shared\Impression;
use DateTimeImmutable;
use function date_create_immutable;

final class ProtocolLine extends AggregatedRoot
{
    public function __construct(
        ProtocolLineId $id,
        private readonly DistanceId $distanceId,
        private readonly ?PersonId $personId,
        private readonly ?ClubId $clubId,
        private readonly ProtocolLineRowData $row,
        Impression $impression,
    ) {
        parent::__construct($id, $impression);
    }

    public function distanceId(): DistanceId
    {
        return $this->distanceId;
    }

    public function personId(): ?PersonId
    {
        return $this->personId;
    }

    public function clubId(): ?ClubId
    {
        return $this->clubId;
    }

    public function row(): ProtocolLineRowData
    {
        return $this->row;
    }

    public function time(): DateTimeImmutable|false
    {
        return date_create_immutable($this->row->time);
    }
}
