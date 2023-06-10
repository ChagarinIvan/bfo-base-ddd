<?php

declare(strict_types=1);

namespace App\Domain\Club\Factory;

use App\Domain\Club\Club;
use App\Domain\Shared\Clock;
use App\Domain\Shared\Impression;

final readonly class StandardClubFactory implements ClubFactory
{
    public function __construct(
        private ClubIdGenerator $idGenerator,
        private Clock $clock,
    ) {
    }

    public function create(ClubInput $input): Club
    {
        return new Club(
            $this->idGenerator->nextId(),
            $input->name,
            new Impression($this->clock->now(), $input->footprint)
        );
    }
}
