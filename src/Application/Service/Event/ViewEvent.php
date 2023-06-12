<?php

declare(strict_types=1);

namespace App\Application\Service\Event;

use App\Domain\Event\EventId;

final readonly class ViewEvent
{
    public function __construct(private string $id)
    {
    }

    public function id(): EventId
    {
        return EventId::fromString($this->id);
    }
}
