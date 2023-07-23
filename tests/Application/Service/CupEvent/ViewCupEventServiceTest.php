<?php

declare(strict_types=1);

namespace Tests\Application\Service\CupEvent;

use App\Application\Dto\CupEvent\CupEventAssembler;
use App\Application\Dto\CupEvent\ViewCupEventDto;
use App\Application\Dto\Shared\AuthAssembler;
use App\Application\Service\CupEvent\Exception\CupEventNotFound;
use App\Application\Service\CupEvent\ViewCupEvent;
use App\Application\Service\CupEvent\ViewCupEventService;
use App\Domain\CupEvent\CupEventId;
use App\Domain\CupEvent\CupEventRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\CupEvent\CupEventFaker;

class ViewCupEventServiceTest extends TestCase
{
    private ViewCupEventService $service;

    private CupEventRepository&MockObject $cupsEvents;

    protected function setUp(): void
    {
        $this->service = new ViewCupEventService(
            $this->cupsEvents = $this->createMock(CupEventRepository::class),
            new CupEventAssembler(new AuthAssembler()),
        );
    }

    /** @test */
    public function it_fails_when_cup_event_not_found(): void
    {
        $this->expectException(CupEventNotFound::class);

        $cupEventId = CupEventId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');

        $this->cupsEvents
            ->expects($this->once())
            ->method('byId')
            ->with($this->equalTo($cupEventId))
            ->willReturn(null)
        ;

        $command = new ViewCupEvent('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $this->service->execute($command);
    }

    /** @test */
    public function it_shows_cup_event(): void
    {
        $cupEventId = CupEventId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $cupEvent = CupEventFaker::fakeCupEvent(id: '6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');

        $this->cupsEvents
            ->expects($this->once())
            ->method('byId')
            ->with($this->equalTo($cupEventId))
            ->willReturn($cupEvent)
        ;

        $command = new ViewCupEvent('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $result = $this->service->execute($command);

        $this->assertInstanceOf(ViewCupEventDto::class, $result);
        $this->assertEquals('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', $result->id);
    }
}
