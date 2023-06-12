<?php

declare(strict_types=1);

namespace App\Domain\CupEvent;

use App\Domain\AggregatedRoot;
use App\Domain\Cup\CupId;
use App\Domain\Event\EventId;
use App\Domain\Shared\Impression;
use App\Domain\Shared\Metadata;

final class CupEvent extends AggregatedRoot
{
    private bool $disabled = false;

    public function __construct(
        CupEventId $id,
        private readonly CupId $cupId,
        private readonly EventId $eventId,
        /** array<cup_group, distance_id[]> $groupsMap */
        private Metadata $groupsMap,
        private readonly float $points,
        Impression $impression,
    ) {
        parent::__construct($id, $impression);
    }

    public function disabled(): bool
    {
        return $this->disabled;
    }

    public function eventId(): EventId
    {
        return $this->eventId;
    }

    public function points(): float
    {
        return $this->points;
    }

    public function disable(Impression $impression): void
    {
        $this->updated = $impression;
        $this->disabled = true;
    }

    public function cupId(): CupId
    {
        return $this->cupId;
    }

    public function groupsMap(): Metadata
    {
        return $this->groupsMap;
    }
}
