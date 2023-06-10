<?php

declare(strict_types=1);

namespace App\Infrastracture\Laravel\Competition;

use App\Domain\Competition\CompetitionId;
use App\Domain\Competition\Factory\CompetitionIdGenerator;
use Illuminate\Support\Str;

final class LaravelStrCompetitionIdGenerator implements CompetitionIdGenerator
{
    public function nextId(): CompetitionId
    {
        return new CompetitionId(Str::uuid());
    }
}
