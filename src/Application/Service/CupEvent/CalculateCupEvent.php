<?php

declare(strict_types=1);

namespace App\Application\Service\CupEvent;

use App\Application\Dto\Shared\TokenFootprint;
use App\Domain\CupEvent\CupEventId;
use App\Domain\Shared\Footprint;

final readonly class CalculateCupEvent
{
    public function __construct(
        private string $id,
        private TokenFootprint $footprint,
    ) {
    }

    public function id(): CupEventId
    {
        return CupEventId::fromString($this->id);
    }

    public function footprint(): Footprint
    {
        return Footprint::fromArray($this->footprint->toArray());
    }
}
