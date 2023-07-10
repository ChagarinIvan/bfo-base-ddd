<?php

declare(strict_types=1);

namespace App\Domain\Cup;

use App\Domain\Shared\Criteria;
use App\Domain\Shared\ListingResult;

interface CupRepository
{
    public function add(Cup $cup): void;

    public function byId(CupId $id): ?Cup;

    public function lockById(CupId $id): ?Cup;

    public function update(Cup $cup): void;

    public function byCriteria(Criteria $criteria): ListingResult;
}
