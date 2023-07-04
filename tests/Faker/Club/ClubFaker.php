<?php

declare(strict_types=1);

namespace Tests\Faker\Club;

use App\Domain\Club\Club;
use App\Domain\Club\ClubId;
use Tests\Faker\Shared\AuthFaker;

class ClubFaker
{
    public static function fakeClub(
        string $id = '1fc7e705-ef72-47b2-ba4e-55779b02c61f',
        string $name = 'tеst сlub',
        bool $disabled = false,
    ): Club {
        $impression = AuthFaker::fakeImpression();

        $club = new Club(
            ClubId::fromString($id),
            $name,
            $impression,
        );

        if ($disabled) {
            $club->disable($impression);
        }

        return $club;
    }
}
