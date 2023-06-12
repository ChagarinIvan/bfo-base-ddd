<?php

declare(strict_types=1);

namespace Tests\Application\Service\Event;

use App\Application\Service\Event\DisableEvent;
use App\Application\Service\Event\DisableEventService;
use App\Application\Service\Event\Exception\EventNotFound;
use App\Domain\Event\EventId;
use App\Domain\Event\EventRepository;
use App\Domain\Shared\DummyTransactional;
use App\Domain\Shared\FrozenClock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\Event\EventFaker;
use Tests\Faker\Shared\AuthFaker;

class DisableEventServiceTest extends TestCase
{
    private DisableEventService $service;

    private EventRepository&MockObject $events;

    protected function setUp(): void
    {
        $this->service = new DisableEventService(
            $this->events = $this->createMock(EventRepository::class),
            new FrozenClock(),
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

        $command = new DisableEvent('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', AuthFaker::fakeFootprintDto());
        $this->service->execute($command);
    }

    /** @test */
    public function it_disables_event(): void
    {
        $eventId = EventId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $event = EventFaker::fakeEvent();

        $this->events
            ->expects($this->once())
            ->method('lockById')
            ->with($this->equalTo($eventId))
            ->willReturn($event)
        ;

        $this->events
            ->expects($this->once())
            ->method('update')
            ->with($this->identicalTo($event))
        ;

        $command = new DisableEvent('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', AuthFaker::fakeFootprintDto());
        $this->service->execute($command);

        $this->assertTrue($event->disabled());
    }
}
