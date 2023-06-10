<?php

declare(strict_types=1);

namespace App\Application\Service\Person;

use App\Application\Dto\Person\PersonInfoDto;
use App\Application\Dto\Shared\TokenFootprint;
use App\Domain\Person\PersonId;
use App\Domain\Person\PersonInfo;
use App\Domain\Shared\Footprint;

final readonly class UpdatePersonInfo
{
    public function __construct(
        private string $id,
        private PersonInfoDto $dto,
        private TokenFootprint $footprint
    ) {
    }

    public function info(): PersonInfo
    {
        return new PersonInfo(
            $this->dto->firstname,
            $this->dto->lastname,
            $this->dto->yearOfBirthday ? (int) $this->dto->yearOfBirthday : null,
        );
    }

    public function footprint(): Footprint
    {
        return Footprint::fromArray($this->footprint->toArray());
    }

    public function id(): PersonId
    {
        return PersonId::fromString($this->id);
    }
}
