<?php

declare(strict_types=1);

namespace App\Domain\CupEvent;

use App\Domain\AggregatedRoot;
use App\Domain\Cup\CupId;
use App\Domain\Event\EventId;
use App\Domain\Shared\Impression;

final class CupEvent extends AggregatedRoot
{
    public function __construct(
        CupEventId $id,
        private readonly CupId $cupId,
        private readonly EventId $eventId,
        private CupEventAttributes $attributes,
        Impression $impression,
    ) {
        parent::__construct($id, $impression);
    }

    public function eventId(): EventId
    {
        return $this->eventId;
    }

    public function points(): float
    {
        return $this->attributes->points;
    }

    public function cupId(): CupId
    {
        return $this->cupId;
    }

    public function groupsDistances(): GroupsDistances
    {
        return $this->attributes->groupsDistances;
    }

    public function updateAttributes(CupEventAttributes $attributes, Impression $impression): void
    {
        $this->attributes = $attributes;
        $this->updated = $impression;
    }
}
