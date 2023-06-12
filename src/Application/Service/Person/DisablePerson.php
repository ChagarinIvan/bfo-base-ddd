<?php

declare(strict_types=1);

namespace App\Application\Service\Person;

use App\Application\Dto\Shared\TokenFootprint;
use App\Domain\Person\PersonId;
use App\Domain\Shared\Footprint;

final readonly class DisablePerson
{
    public function __construct(
        private string $id,
        private TokenFootprint $footprint,
    ) {
    }

    public function id(): PersonId
    {
        return PersonId::fromString($this->id);
    }

    public function footprint(): Footprint
    {
        return Footprint::fromArray($this->footprint->toArray());
    }
}
