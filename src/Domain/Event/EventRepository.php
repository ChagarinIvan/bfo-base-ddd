<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\Shared\Criteria;
use App\Domain\Shared\ListingResult;

interface EventRepository
{
    public function add(Event $event): void;

    public function lockById(EventId $id): ?Event;

    public function update(Event $event): void;

    public function byId(EventId $id): ?Event;

    public function byCriteria(Criteria $criteria): ListingResult;
}
