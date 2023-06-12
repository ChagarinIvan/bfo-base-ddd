<?php

declare(strict_types=1);

namespace Tests\Application\Service\Event;

use App\Application\Dto\Event\EventAssembler;
use App\Application\Dto\Event\ViewEventDto;
use App\Application\Dto\Shared\AuthAssembler;
use App\Application\Service\Event\Exception\EventNotFound;
use App\Application\Service\Event\ViewEvent;
use App\Application\Service\Event\ViewEventService;
use App\Domain\Event\EventId;
use App\Domain\Event\EventRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\Event\EventFaker;

class ViewEventServiceTest extends TestCase
{
    private ViewEventService $service;

    private EventRepository&MockObject $events;

    protected function setUp(): void
    {
        $this->service = new ViewEventService(
            $this->events = $this->createMock(EventRepository::class),
            new EventAssembler(new AuthAssembler()),
        );
    }

    /** @test */
    public function it_fails_when_event_not_found(): void
    {
        $this->expectException(EventNotFound::class);

        $eventId = EventId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');

        $this->events
            ->expects($this->once())
            ->method('byId')
            ->with($this->equalTo($eventId))
            ->willReturn(null)
        ;

        $command = new ViewEvent('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $this->service->execute($command);
    }

    /** @test */
    public function it_shows_competition(): void
    {
        $eventId = EventId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $event = EventFaker::fakeEvent(id: '6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');

        $this->events
            ->expects($this->once())
            ->method('byId')
            ->with($this->equalTo($eventId))
            ->willReturn($event)
        ;

        $command = new ViewEvent('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $result = $this->service->execute($command);

        $this->assertInstanceOf(ViewEventDto::class, $result);
        $this->assertEquals('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', $result->id);
    }
}
