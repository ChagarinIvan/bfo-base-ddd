<?php

declare(strict_types=1);

namespace Tests\Application\Service\Event;

use App\Application\Dto\Event\EventAssembler;
use App\Application\Dto\Event\EventInfoDto;
use App\Application\Dto\Shared\AuthAssembler;
use App\Application\Service\Event\Exception\EventNotFound;
use App\Application\Service\Event\UpdateEventInfo;
use App\Application\Service\Event\UpdateEventInfoService;
use App\Domain\Event\EventId;
use App\Domain\Event\EventRepository;
use App\Domain\Shared\DummyTransactional;
use App\Domain\Shared\FrozenClock;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\Event\EventFaker;
use Tests\Faker\Shared\AuthFaker;

class UpdateEventInfoServiceTest extends TestCase
{
    private UpdateEventInfoService $service;

    private EventRepository&MockObject $events;

    protected function setUp(): void
    {
        $this->service = new UpdateEventInfoService(
            $this->events = $this->createMock(EventRepository::class),
            new FrozenClock(new DateTimeImmutable('2023-04-01')),
            new EventAssembler(new AuthAssembler()),
            new DummyTransactional(),
        );
    }

    /** @test */
    public function it_fails_when_event_not_found(): void
    {
        $this->expectException(EventNotFound::class);

        $eventId = EventId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');

        $this->events
            ->expects($this->once())
            ->method('lockById')
            ->with($this->equalTo($eventId))
            ->willReturn(null)
        ;

        $info = new EventInfoDto();
        $info->name = 'test event';
        $info->description = 'test event description';
        $info->date = '2023-01-01';

        $command = new UpdateEventInfo('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', $info, AuthFaker::fakeFootprintDto());
        $this->service->execute($command);
    }

    /** @test */
    public function it_updates_person_info(): void
    {
        $eventId = EventId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $event = EventFaker::fakeEvent(id: '6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');

        $this->events
            ->expects($this->once())
            ->method('lockById')
            ->with($this->equalTo($eventId))
            ->willReturn($event)
        ;

        $this->events->expects($this->once())->method('update');

        $info = new EventInfoDto();
        $info->name = 'test event new';
        $info->description = 'test event';
        $info->date = '2023-02-02';

        $command = new UpdateEventInfo('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', $info, AuthFaker::fakeFootprintDto());
        $event = $this->service->execute($command);

        $this->assertEquals('test event new', $event->name);
        $this->assertEquals('test event', $event->description);
        $this->assertEquals(new DateTimeImmutable('2023-02-02'), $event->date);
        $this->assertEquals(new DateTimeImmutable('2023-04-01'), $event->updated->at);
    }
}
