<?php

declare(strict_types=1);

namespace Tests\Application\Service\CupEvent;

use App\Application\Dto\CupEvent\CalculateCupEventDto;
use App\Application\Dto\CupEvent\CupEventAssembler;
use App\Application\Dto\CupEvent\ViewCupEventPointsDto;
use App\Application\Service\CupEvent\CalculateCupEvent;
use App\Application\Service\CupEvent\CalculateCupEventService;
use App\Application\Service\CupEvent\Exception\CupEventNotFound;
use App\Application\Service\CupEvent\Exception\UnableToCalculateCupEvent;
use App\Domain\CupEvent\Calculator\CupEventCalculator;
use App\Domain\CupEvent\Calculator\Exception\CupNotExist;
use App\Domain\CupEvent\CupEventId;
use App\Domain\CupEvent\CupEventPoints;
use App\Domain\CupEvent\CupEventRepository;
use App\Domain\Person\PersonId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\CupEvent\CupEventFaker;
use Tests\Faker\Shared\AuthFaker;

class CalculateCupEventServiceTest extends TestCase
{
    private CalculateCupEventService $service;

    private CupEventRepository&MockObject $cupEvents;

    private CupEventCalculator&MockObject $calculator;

    protected function setUp(): void
    {
        $this->service = new CalculateCupEventService(
            $this->cupEvents = $this->createMock(CupEventRepository::class),
            $this->calculator = $this->createMock(CupEventCalculator::class),
            new CupEventAssembler(),
        );
    }

    /** @test */
    public function it_fails_when_cup_event_not_found(): void
    {
        $this->expectException(CupEventNotFound::class);

        $cupEventId = CupEventId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');

        $this->cupEvents
            ->expects($this->once())
            ->method('byId')
            ->with($this->equalTo($cupEventId))
            ->willReturn(null)
        ;

        $this->service->execute($this->makeCommand());
    }

    /** @test */
    public function it_fails_when_cup_not_exist(): void
    {
        $this->expectException(UnableToCalculateCupEvent::class);
        $this->expectExceptionMessage('Unable to calculate cup event. Reason: ');

        $cupEventId = CupEventId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $cupEvent = CupEventFaker::fakeCupEvent();

        $this->cupEvents
            ->expects($this->once())
            ->method('byId')
            ->with($this->equalTo($cupEventId))
            ->willReturn($cupEvent)
        ;

        $this->calculator
            ->expects($this->once())
            ->method('calculate')
            ->with($this->identicalTo($cupEvent))
            ->willThrowException(new CupNotExist())
        ;

        $this->service->execute($this->makeCommand());
    }

    /** @test */
    public function it_calculates_cup_event(): void
    {
        $cupEventId = CupEventId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $cupEvent = CupEventFaker::fakeCupEvent();

        $this->cupEvents
            ->expects($this->once())
            ->method('byId')
            ->with($this->equalTo($cupEventId))
            ->willReturn($cupEvent)
        ;

        $points = [
            new CupEventPoints($cupEventId, PersonId::random(), 1100),
            new CupEventPoints($cupEventId, PersonId::random(), 987),
            new CupEventPoints($cupEventId, PersonId::random(), 678),
        ];

        $this->calculator
            ->expects($this->once())
            ->method('calculate')
            ->with($this->identicalTo($cupEvent))
            ->willReturn($points)
        ;

        $pointsDtoList = $this->service->execute($this->makeCommand());

        $this->assertContainsOnlyInstancesOf(ViewCupEventPointsDto::class, $pointsDtoList);
        $this->assertCount(3, $pointsDtoList);
    }

    private function makeCommand(): CalculateCupEvent
    {
        $dto = new CalculateCupEventDto();
        $dto->id = '6deb7e98-b55d-4214-98ac-fc3a16e3ec6b';
        $dto->group = 'M_21';

        return new CalculateCupEvent($dto, AuthFaker::fakeFootprintDto());
    }
}
