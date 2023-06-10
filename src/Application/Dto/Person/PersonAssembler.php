<?php

declare(strict_types=1);

namespace App\Application\Dto\Person;

use App\Application\Dto\Shared\AuthAssembler;
use App\Domain\Person\Person;

final readonly class PersonAssembler
{
    public function __construct(private AuthAssembler $authAssembler)
    {
    }

    public function toViewPersonDto(Person $person): ViewPersonDto
    {
        $dto = new ViewPersonDto();
        $dto->id = $person->id()->toString();
        $dto->firstname = $person->info()->firstname;
        $dto->lastname = $person->info()->lastname;
        $dto->yearOfBirthday = $person->info()->yearOfBirthday;
        $dto->clubId = $person->clubId()?->toString();
        $dto->activeRankId = $person->activeRankId()?->toString();
        $dto->attributes = $person->attributes()->toArray();
        $dto->updated = $this->authAssembler->toImpressionDto($person->updated());
        $dto->created = $this->authAssembler->toImpressionDto($person->created());

        return $dto;
    }
}
