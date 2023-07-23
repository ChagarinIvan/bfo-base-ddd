<?php

declare(strict_types=1);

namespace App\Domain\CupEvent\Factory;

use App\Domain\CupEvent\CupEvent;
use App\Domain\Shared\Clock;
use App\Domain\Shared\Impression;

final readonly class StandardCupEventFactory implements CupEventFactory
{
    public function __construct(
        private CupEventIdGenerator $idGenerator,
        private Clock $clock,
    ) {
    }

    public function create(CupEventInput $input): CupEvent
    {
        return new CupEvent(
            $this->idGenerator->nextId(),
            $input->cupId,
            $input->eventId,
            $input->attributes,
            new Impression($this->clock->now(), $input->footprint),
        );
    }
}
