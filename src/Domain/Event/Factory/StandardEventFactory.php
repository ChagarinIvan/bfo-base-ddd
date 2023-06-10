<?php

declare(strict_types=1);

namespace App\Domain\Event\Factory;

use App\Domain\Event\Event;
use App\Domain\Shared\Clock;
use App\Domain\Shared\Impression;

final readonly class StandardEventFactory implements EventFactory
{
    public function __construct(
        private EventIdGenerator $idGenerator,
        private Clock $clock,
    ) {
    }

    public function create(EventInput $input): Event
    {
        return new Event(
            $this->idGenerator->nextId(),
            $input->competitionId,
            $input->info,
            new Impression($this->clock->now(), $input->footprint),
        );
    }
}
