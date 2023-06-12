<?php

declare(strict_types=1);

namespace App\Application\Service\Competition;

use App\Domain\Competition\CompetitionId;

final readonly class ViewCompetition
{
    public function __construct(private string $id)
    {
    }

    public function id(): CompetitionId
    {
        return CompetitionId::fromString($this->id);
    }
}
