<?php

declare(strict_types=1);

namespace Tests\Faker\Event;

use App\Domain\Competition\CompetitionId;
use App\Domain\Event\Event;
use App\Domain\Event\EventId;
use App\Domain\Event\EventInfo;
use DateTimeImmutable;
use Tests\Faker\Shared\AuthFaker;

class EventFaker
{
    public static function fakeEvent(
        string $id = '1fc7e705-ef72-47b2-ba4e-55779b02c61f',
        string $competitionId = '1efaf3e4-a661-4a68-b014-669e03d1f895',
        string $name = 'test event',
        string $description = 'test event description',
        string $date = '2023-01-01',
    ): Event {
        return new Event(
            EventId::fromString($id),
            CompetitionId::fromString($competitionId),
            new EventInfo(
                $name,
                $description,
                new DateTimeImmutable($date),
            ),
            AuthFaker::fakeImpression()
        );
    }
}
