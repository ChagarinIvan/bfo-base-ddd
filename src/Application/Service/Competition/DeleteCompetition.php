<?php

declare(strict_types=1);

namespace App\Application\Service\Competition;

use App\Application\Dto\Shared\TokenFootprint;
use App\Domain\Competition\CompetitionId;
use App\Domain\Shared\Footprint;

final readonly class DeleteCompetition
{
    public function __construct(
        private string $id,
        private TokenFootprint $footprint,
    ) {
    }

    public function id(): CompetitionId
    {
        return CompetitionId::fromString($this->id);
    }

    public function footprint(): Footprint
    {
        return Footprint::fromArray($this->footprint->toArray());
    }
}
