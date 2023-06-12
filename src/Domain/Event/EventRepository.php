<?php

declare(strict_types=1);

namespace App\Domain\Event;

interface EventRepository
{
    public function add(Event $event): void;

    public function lockById(EventId $id): ?Event;

    public function update(Event $event): void;

    public function byId(EventId $id): ?Event;
}
