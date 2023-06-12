<?php

declare(strict_types=1);

namespace App\Domain\Competition;

use App\Domain\Shared\Criteria;

interface CompetitionRepository
{
    public function add(Competition $competition): void;

    public function lockById(CompetitionId $id): ?Competition;

    public function update(Competition $competition): void;

    public function byId(CompetitionId $id): ?Competition;

    /** @return Competition[] */
    public function byCriteria(Criteria $criteria): array;
}
