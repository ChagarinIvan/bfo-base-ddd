<?php

declare(strict_types=1);

namespace App\Domain\Club;

use App\Domain\Club\Factory\ClubInput;
use App\Domain\Shared\Clock;
use App\Domain\Shared\Impression;
use App\Domain\Shared\Normalizer\Normalizer;

final readonly class Updater
{
    public function __construct(
        private Normalizer $normalizer,
        private Clock $clock,
    ) {
    }

    public function update(ClubInput $input, Club $club): void
    {
        $club->update(
            $input->withName($this->normalizer->normalize($input->name)),
            new Impression($this->clock->now(), $input->footprint),
        );
    }
}
