<?php

declare(strict_types=1);

namespace Tests\Faker\Competition;

use App\Domain\Competition\Competition;
use App\Domain\Competition\CompetitionId;
use App\Domain\Competition\CompetitionInfo;
use DateTimeImmutable;
use Tests\Faker\Shared\AuthFaker;

class CompetitionFaker
{
    public static function fakeCompetition(
        string $id = '1fc7e705-ef72-47b2-ba4e-55779b02c61f',
        string $name = 'test competition',
        string $description = 'test competition description',
        string $from = '2023-01-01',
        string $to = '2023-01-02',
    ): Competition {
        return new Competition(
            CompetitionId::fromString($id),
            new CompetitionInfo(
                $name,
                $description,
                new DateTimeImmutable($from),
                new DateTimeImmutable($to),
            ),
            AuthFaker::fakeImpression()
        );
    }
}
