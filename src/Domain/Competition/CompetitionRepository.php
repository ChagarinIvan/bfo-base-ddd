<?php

declare(strict_types=1);

namespace App\Domain\Competition;

use App\Domain\Shared\Criteria;
use App\Domain\Shared\ListingResult;

interface CompetitionRepository
{
    public function add(Competition $competition): void;

    public function lockById(CompetitionId $id): ?Competition;

    public function update(Competition $competition): void;

    public function byId(CompetitionId $id): ?Competition;

    public function byCriteria(Criteria $criteria): ListingResult;
}
