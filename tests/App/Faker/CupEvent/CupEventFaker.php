<?php

declare(strict_types=1);

namespace Tests\Faker\CupEvent;

use App\Domain\Cup\CupId;
use App\Domain\CupEvent\CupEvent;
use App\Domain\CupEvent\CupEventId;
use App\Domain\Event\EventId;
use Tests\Faker\Shared\AuthFaker;

class CupEventFaker
{
    public static function fakeCupEvent(
        string $id = '1fc7e705-ef72-47b2-ba4e-55779b02c61f',
        string $cupId = '1efaf3e4-a661-4a68-b014-669e03d1f895',
        string $eventId = '56e6fb03-5869-427e-9bd3-14d8695500cf',
    ): CupEvent {
        return new CupEvent(
            CupEventId::fromString($id),
            CupId::fromString($cupId),
            EventId::fromString($eventId),
            1100,
            AuthFaker::fakeImpression()
        );
    }
}
