<?php

declare(strict_types=1);

namespace App\Domain\CupEvent;

use App\Domain\Shared\Criteria;
use App\Domain\Shared\ListingResult;

interface CupEventRepository
{
    public function byId(CupEventId $id): ?CupEvent;

    public function lockById(CupEventId $id): ?CupEvent;

    public function oneByCriteria(Criteria $criteria): ?CupEvent;

    public function byCriteria(Criteria $criteria): ListingResult;

    public function add(CupEvent $cupEvent): void;

    public function update(CupEvent $cupEvent): void;
}
