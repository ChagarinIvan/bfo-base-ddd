<?php

declare(strict_types=1);

namespace App\Domain\CupEvent\Factory;

use App\Domain\Cup\CupId;
use App\Domain\CupEvent\CupEventAttributes;
use App\Domain\Event\EventId;
use App\Domain\Shared\Footprint;

final readonly class CupEventInput
{
    public function __construct(
        public CupId $cupId,
        public EventId $eventId,
        public CupEventAttributes $attributes,
        public Footprint $footprint,
    ) {
    }
}
