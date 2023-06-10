<?php

declare(strict_types=1);

namespace App\Tests\Faker\Person;

use App\Domain\Club\ClubId;
use App\Domain\Person\Person;
use App\Domain\Person\PersonId;
use App\Domain\Person\PersonInfo;
use App\Domain\Shared\Metadata;
use Tests\Faker\Shared\AuthFaker;

class PersonFaker
{
    public static function fakePerson(
        string $id = '1fc7e705-ef72-47b2-ba4e-55779b02c61f',
        string $firstname = 'test firstname',
        string $lastname = 'test lastname',
        int $yearOfBirthday = 1988,
        bool $disabled = false,
        string $clubId = null,
    ): Person {
        $impression = AuthFaker::fakeImpression();

        $person = new Person(
            PersonId::fromString($id),
            new PersonInfo(
                $firstname,
                $lastname,
                $yearOfBirthday,
            ),
            $clubId ? ClubId::fromString($clubId) : ClubId::random(),
            Metadata::empty(),
            $impression,
        );

        if ($disabled) {
            $person->disable($impression);
        }

        return $person;
    }
}
