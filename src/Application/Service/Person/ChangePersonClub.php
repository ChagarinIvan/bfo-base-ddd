<?php

declare(strict_types=1);

namespace App\Application\Service\Person;

use App\Application\Dto\Shared\TokenFootprint;
use App\Domain\Club\ClubId;
use App\Domain\Person\PersonId;
use App\Domain\Shared\Footprint;

final readonly class ChangePersonClub
{
    public function __construct(
        private string $id,
        private ?string $clubId,
        private TokenFootprint $footprint,
    ) {
    }

    public function id(): PersonId
    {
        return PersonId::fromString($this->id);
    }

    public function clubId(): ?ClubId
    {
        return $this->clubId ? ClubId::fromString($this->clubId) : null;
    }

    public function footprint(): Footprint
    {
        return Footprint::fromArray($this->footprint->toArray());
    }
}
