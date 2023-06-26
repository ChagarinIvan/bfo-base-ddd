<?php

declare(strict_types=1);

namespace Tests\Faker\ProtocolLine;

use App\Domain\Club\ClubId;
use App\Domain\Distance\DistanceId;
use App\Domain\Person\PersonId;
use App\Domain\ProtocolLine\ProtocolLine;
use App\Domain\ProtocolLine\ProtocolLineId;
use App\Domain\ProtocolLine\ProtocolLineRowData;
use Tests\Faker\Shared\AuthFaker;

class ProtocolLineFaker
{
    public static function fakeProtocolLine(
        string $id = '1fc7e705-ef72-47b2-ba4e-55779b02c61f',
        string $distanceId = '1efaf3e4-a661-4a68-b014-669e03d1f895',
        string $personId = null,
        string $time = '00:21:15',
    ): ProtocolLine {
        return new ProtocolLine(
            id: ProtocolLineId::fromString($id),
            distanceId: DistanceId::fromString($distanceId),
            personId: $personId ? PersonId::fromString($personId) : null,
            clubId: ClubId::random(),
            row: new ProtocolLineRowData(
                serialNumber: '21',
                firstname: 'test firstname',
                lastname: 'test lsatname',
                club: 'test club',
                year: '2000',
                rank: 'II',
                runnerNumber: '212',
                time: $time,
                place: '3',
                completedRank: 'II',
                points: '197',
                outOfCompetition: '',
            ),
            impression: AuthFaker::fakeImpression()
        );
    }
}
