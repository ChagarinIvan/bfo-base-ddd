<?php

declare(strict_types=1);

namespace App\Domain\Person\Factory;

use App\Domain\Person\Person;
use App\Domain\Shared\Clock;
use App\Domain\Shared\Impression;

final readonly class StandardPersonFactory implements PersonFactory
{
    public function __construct(
        private PersonIdGenerator $idGenerator,
        private Clock $clock,
    ) {
    }

    public function create(PersonInput $input): Person
    {
        return new Person(
            $this->idGenerator->nextId(),
            $input->info,
            $input->clubId,
            $input->attributes,
            new Impression($this->clock->now(), $input->footprint),
        );
    }
}
