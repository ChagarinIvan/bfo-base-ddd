<?php

declare(strict_types=1);

namespace App\Application\Service\Person;

use App\Application\Dto\Person\PersonDto;
use App\Application\Dto\Shared\TokenFootprint;
use App\Domain\Club\ClubId;
use App\Domain\Person\Factory\PersonInput;
use App\Domain\Person\PersonInfo;
use App\Domain\Shared\Footprint;
use App\Domain\Shared\Metadata;

final readonly class AddPerson
{
    public function __construct(
        private PersonDto $dto,
        private TokenFootprint $footprint,
    ) {
    }

    public function personInput(): PersonInput
    {
        return new PersonInput(
            $this->info(),
            Metadata::fromArray($this->dto->attributes),
            $this->footprint(),
            $this->dto->clubId ? ClubId::fromString($this->dto->clubId) : null,
        );
    }

    private function footprint(): Footprint
    {
        return Footprint::fromArray($this->footprint->toArray());
    }

    private function info(): PersonInfo
    {
        return new PersonInfo(
            $this->dto->info->firstname,
            $this->dto->info->lastname,
            $this->dto->info->yearOfBirthday ? (int) $this->dto->info->yearOfBirthday : null,
        );
    }
}
