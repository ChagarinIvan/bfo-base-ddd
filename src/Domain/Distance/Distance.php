<?php

declare(strict_types=1);

namespace App\Domain\Distance;

use App\Domain\AggregatedRoot;
use App\Domain\Event\EventId;
use App\Domain\Shared\Impression;
use App\Domain\Shared\Metadata;

final class Distance extends AggregatedRoot
{
    public function __construct(
        DistanceId $id,
        private readonly EventId $eventId,
        private readonly string $name,
        private readonly Metadata $attributes,
        Impression $impression,
    ) {
        parent::__construct($id, $impression);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function eventId(): EventId
    {
        return $this->eventId;
    }

    public function attributes(): Metadata
    {
        return $this->attributes;
    }
}
