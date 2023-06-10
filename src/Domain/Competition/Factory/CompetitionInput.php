<?php

declare(strict_types=1);

namespace App\Domain\Competition\Factory;

use App\Domain\Competition\CompetitionInfo;
use App\Domain\Shared\Footprint;

final readonly class CompetitionInput
{
    public function __construct(
        public CompetitionInfo $info,
        public Footprint $footprint,
    ) {
    }
}
