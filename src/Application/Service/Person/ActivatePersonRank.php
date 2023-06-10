<?php

declare(strict_types=1);

namespace App\Application\Service\Person;

use App\Application\Dto\Shared\TokenFootprint;
use App\Domain\Person\PersonId;
use App\Domain\Rank\RankId;
use App\Domain\Shared\Footprint;

final readonly class ActivatePersonRank
{
    public function __construct(
        private string $id,
        private ?string $rankId,
        private TokenFootprint $footprint,
    ) {
    }

    public function id(): PersonId
    {
        return PersonId::fromString($this->id);
    }

    public function rankId(): ?RankId
    {
        return $this->rankId ? RankId::fromString($this->rankId) : null;
    }

    public function footprint(): Footprint
    {
        return Footprint::fromArray($this->footprint->toArray());
    }
}
