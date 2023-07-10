<?php

declare(strict_types=1);

namespace App\Domain\Cup\Factory;

use App\Domain\Cup\Cup;
use App\Domain\Shared\Clock;
use App\Domain\Shared\Impression;

final readonly class StandardCupFactory implements CupFactory
{
    public function __construct(
        private CupIdGenerator $idGenerator,
        private Clock $clock,
    ) {
    }

    public function create(CupInput $input): Cup
    {
        return new Cup(
            $this->idGenerator->nextId(),
            $input->info,
            new Impression($this->clock->now(), $input->footprint),
        );
    }
}
