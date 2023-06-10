<?php

declare(strict_types=1);

namespace App\Domain\Competition\Factory;

use App\Domain\Competition\Competition;
use App\Domain\Shared\Clock;
use App\Domain\Shared\Impression;

final readonly class StandardCompetitionFactory implements CompetitionFactory
{
    public function __construct(
        private CompetitionIdGenerator $idGenerator,
        private Clock $clock,
    ) {
    }

    public function create(CompetitionInput $input): Competition
    {
        return new Competition(
            $this->idGenerator->nextId(),
            $input->info,
            new Impression($this->clock->now(), $input->footprint)
        );
    }
}
