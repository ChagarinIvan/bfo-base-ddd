<?php

declare(strict_types=1);

namespace App\Application\Service\Club;

use App\Application\Dto\Club\ClubDto;
use App\Application\Dto\Shared\TokenFootprint;
use App\Domain\Club\ClubId;
use App\Domain\Club\UpdateInput;
use App\Domain\Shared\Footprint;

final readonly class UpdateClub
{
    public function __construct(
        private string $id,
        private ClubDto $club,
        private TokenFootprint $footprint,
    ) {
    }

    public function footprint(): Footprint
    {
        return Footprint::fromArray($this->footprint->toArray());
    }

    public function id(): ClubId
    {
        return ClubId::fromString($this->id);
    }

    public function input(): UpdateInput
    {
        return new UpdateInput($this->club->name);
    }
}
