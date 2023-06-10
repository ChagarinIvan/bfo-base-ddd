<?php

declare(strict_types=1);

namespace App\Domain\Event\Factory;

use App\Domain\Competition\CompetitionId;
use App\Domain\Event\EventInfo;
use App\Domain\Shared\Footprint;

final readonly class EventInput
{
    public function __construct(
        public EventInfo $info,
        public CompetitionId $competitionId,
        public Footprint $footprint,
    ) {
    }
}
