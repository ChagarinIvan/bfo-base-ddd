<?php

declare(strict_types=1);

namespace Tests\Faker\CupEvent;

use App\Domain\Cup\CupId;
use App\Domain\CupEvent\CupEvent;
use App\Domain\CupEvent\CupEventId;
use App\Domain\Event\EventId;
use App\Domain\Shared\Metadata;
use Tests\Faker\Shared\AuthFaker;

class CupEventFaker
{
    public static function fakeCupEvent(
        string $id = '1fc7e705-ef72-47b2-ba4e-55779b02c61f',
        string $cupId = '1efaf3e4-a661-4a68-b014-669e03d1f895',
        string $eventId = '56e6fb03-5869-427e-9bd3-14d8695500cf',
    ): CupEvent {
        return new CupEvent(
            id: CupEventId::fromString($id),
            cupId: CupId::fromString($cupId),
            eventId: EventId::fromString($eventId),
            groupsMap: Metadata::fromArray([
                'M_21' => ['b5f58bfd-1335-4e0c-8233-7dc2ab82181f', 'bb3bf8fc-929b-4769-9dad-9fc147a5b87f'],
            ]),
            points: 1100,
            impression: AuthFaker::fakeImpression()
        );
    }
}
