<?php

declare(strict_types=1);

namespace App\Domain\Club;

use App\Domain\Shared\Criteria;

interface ClubRepository
{
    public function add(Club $club): void;

    public function lockById(ClubId $id): ?Club;

    public function update(Club $club): void;

    public function oneByCriteria(Criteria $criteria): ?Club;
}
