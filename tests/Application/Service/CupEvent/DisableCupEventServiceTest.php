<?php

declare(strict_types=1);

namespace Tests\Application\Service\CupEvent;

use App\Application\Service\CupEvent\DisableCupEvent;
use App\Application\Service\CupEvent\DisableCupEventService;
use App\Application\Service\CupEvent\Exception\CupEventNotFound;
use App\Domain\CupEvent\CupEventId;
use App\Domain\CupEvent\CupEventRepository;
use App\Domain\Shared\DummyTransactional;
use App\Domain\Shared\FrozenClock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\CupEvent\CupEventFaker;
use Tests\Faker\Shared\AuthFaker;

class DisableCupEventServiceTest extends TestCase
{
    private DisableCupEventService $service;

    private CupEventRepository&MockObject $cupsEvents;

    protected function setUp(): void
    {
        $this->service = new DisableCupEventService(
            $this->cupsEvents = $this->createMock(CupEventRepository::class),
            new FrozenClock(),
            new DummyTransactional(),
        );
    }

    /** @test */
    public function it_fails_when_cup_event_not_found(): void
    {
        $this->expectException(CupEventNotFound::class);

        $cupEventId = CupEventId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');

        $this->cupsEvents
            ->expects($this->once())
            ->method('lockById')
            ->with($this->equalTo($cupEventId))
            ->willReturn(null)
        ;

        $command = new DisableCupEvent('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', AuthFaker::fakeFootprintDto());
        $this->service->execute($command);
    }

    /** @test */
    public function it_disables_cup_event(): void
    {
        $cupEventId = CupEventId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $cupEvent = CupEventFaker::fakeCupEvent();

        $this->cupsEvents
            ->expects($this->once())
            ->method('lockById')
            ->with($this->equalTo($cupEventId))
            ->willReturn($cupEvent)
        ;

        $this->cupsEvents
            ->expects($this->once())
            ->method('update')
            ->with($this->identicalTo($cupEvent))
        ;

        $command = new DisableCupEvent('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', AuthFaker::fakeFootprintDto());
        $this->service->execute($command);

        $this->assertTrue($cupEvent->disabled());
    }
}
