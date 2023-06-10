<?php

declare(strict_types=1);

namespace App\Domain\Competition\Factory;

use App\Domain\Competition\CompetitionId;

interface CompetitionIdGenerator
{
    public function nextId(): CompetitionId;
}
