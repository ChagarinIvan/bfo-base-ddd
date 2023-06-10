<?php

declare(strict_types=1);

namespace App\Application\Service\Event;

use App\Application\Dto\Shared\TokenFootprint;
use App\Domain\Event\EventId;
use App\Domain\Shared\Footprint;

final readonly class DeleteEvent
{
    public function __construct(
        private string $id,
        private TokenFootprint $footprint,
    ) {
    }

    public function id(): EventId
    {
        return EventId::fromString($this->id);
    }

    public function footprint(): Footprint
    {
        return Footprint::fromArray($this->footprint->toArray());
    }
}
