<?php

declare(strict_types=1);

namespace App\Application\Service\Cup;

use App\Application\Dto\Shared\TokenFootprint;
use App\Domain\Cup\CupId;
use App\Domain\Shared\Footprint;

final readonly class DisableCup
{
    public function __construct(
        private string $id,
        private TokenFootprint $footprint,
    ) {
    }

    public function id(): CupId
    {
        return CupId::fromString($this->id);
    }

    public function footprint(): Footprint
    {
        return Footprint::fromArray($this->footprint->toArray());
    }
}
