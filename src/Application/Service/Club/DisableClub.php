<?php

declare(strict_types=1);

namespace App\Application\Service\Club;

use App\Application\Dto\Shared\TokenFootprint;
use App\Domain\Club\ClubId;
use App\Domain\Shared\Footprint;

final readonly class DisableClub
{
    public function __construct(
        private string $id,
        private TokenFootprint $footprint,
    ) {
    }

    public function id(): ClubId
    {
        return ClubId::fromString($this->id);
    }

    public function footprint(): Footprint
    {
        return Footprint::fromArray($this->footprint->toArray());
    }
}
