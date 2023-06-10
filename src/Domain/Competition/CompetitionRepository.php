<?php

declare(strict_types=1);

namespace App\Domain\Competition;

interface CompetitionRepository
{
    public function add(Competition $competition): void;

    public function lockById(CompetitionId $id): ?Competition;

    public function update(Competition $competition): void;
}
