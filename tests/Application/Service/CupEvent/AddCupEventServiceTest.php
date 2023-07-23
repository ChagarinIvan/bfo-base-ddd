<?php

declare(strict_types=1);

namespace Tests\Application\Service\CupEvent;

use App\Application\Dto\CupEvent\CupEventAssembler;
use App\Application\Dto\CupEvent\CupEventAttributesDto;
use App\Application\Dto\CupEvent\CupEventDto;
use App\Application\Dto\CupEvent\GroupDistancesDto;
use App\Application\Dto\Shared\AuthAssembler;
use App\Application\Service\CupEvent\AddCupEvent;
use App\Application\Service\CupEvent\AddCupEventService;
use App\Application\Service\CupEvent\Exception\FailedToAddCupEvent;
use App\Domain\Cup\CupId;
use App\Domain\CupEvent\CupEventAttributes;
use App\Domain\CupEvent\CupEventRepository;
use App\Domain\CupEvent\Exception\CupEventAlreadyExist;
use App\Domain\CupEvent\Factory\CupEventFactory;
use App\Domain\CupEvent\Factory\CupEventInput;
use App\Domain\CupEvent\GroupDistances;
use App\Domain\CupEvent\GroupsDistances;
use App\Domain\Distance\DistanceId;
use App\Domain\Event\EventId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\CupEvent\CupEventFaker;
use Tests\Faker\Shared\AuthFaker;

class AddCupEventServiceTest extends TestCase
{
    private AddCupEventService $service;

    private CupEventFactory&MockObject $factory;

    private CupEventRepository&MockObject $cupsEvents;

    protected function setUp(): void
    {
        $this->service = new AddCupEventService(
            $this->factory = $this->createMock(CupEventFactory::class),
            $this->cupsEvents = $this->createMock(CupEventRepository::class),
            new CupEventAssembler(new AuthAssembler()),
        );
    }

    /** @test */
    public function it_fails_when_cup_event_already_exist(): void
    {
        $this->expectException(FailedToAddCupEvent::class);
        $this->expectExceptionMessage('Unable to add cup event. Reason: Cup event for cup "1efaf3e4-a661-4a68-b014-669e03d1f895" on event "56e6fb03-5869-427e-9bd3-14d8695500cf" already exist.');

        $input = new CupEventInput(
            CupId::fromString('1fc7e705-ef72-47b2-ba4e-55779b02c61f'),
            EventId::fromString('1efaf3e4-a661-4a68-b014-669e03d1f895'),
            new CupEventAttributes(
                new GroupsDistances([
                    new GroupDistances('M_21', [
                        DistanceId::fromString('b5f58bfd-1335-4e0c-8233-7dc2ab82181f'),
                        DistanceId::fromString('bb3bf8fc-929b-4769-9dad-9fc147a5b87f'),
                    ]),
                ]),
                0.9
            ),
            AuthFaker::fakeFootprint(),
        );

        $cupEvent = CupEventFaker::fakeCupEvent();

        $this->factory
            ->expects($this->once())
            ->method('create')
            ->with($this->equalTo($input))
            ->willThrowException(CupEventAlreadyExist::byCupEvent($cupEvent))
        ;

        $this->cupsEvents
            ->expects($this->never())
            ->method('add')
        ;

        $cupEventDto = $this->service->execute($this->stubCommand());

        $this->assertEquals($cupEvent->id()->toString(), $cupEventDto->id);
    }

    /** @test */
    public function it_creates_cup_event(): void
    {
        $input = new CupEventInput(
            CupId::fromString('1fc7e705-ef72-47b2-ba4e-55779b02c61f'),
            EventId::fromString('1efaf3e4-a661-4a68-b014-669e03d1f895'),
            new CupEventAttributes(
                new GroupsDistances([
                    new GroupDistances('M_21', [
                        DistanceId::fromString('b5f58bfd-1335-4e0c-8233-7dc2ab82181f'),
                        DistanceId::fromString('bb3bf8fc-929b-4769-9dad-9fc147a5b87f'),
                    ]),
                ]),
                0.9
            ),
            AuthFaker::fakeFootprint(),
        );

        $cupEvent = CupEventFaker::fakeCupEvent();

        $this->factory
            ->expects($this->once())
            ->method('create')
            ->with($this->equalTo($input))
            ->willReturn($cupEvent)
        ;

        $this->cupsEvents
            ->expects($this->once())
            ->method('add')
            ->with($this->identicalTo($cupEvent))
        ;

        $cupEventDto = $this->service->execute($this->stubCommand());

        $this->assertEquals($cupEvent->id()->toString(), $cupEventDto->id);
    }

    private function stubCommand(): AddCupEvent
    {
        $dto = new CupEventDto();
        $dto->cupId = '1fc7e705-ef72-47b2-ba4e-55779b02c61f';
        $dto->eventId = '1efaf3e4-a661-4a68-b014-669e03d1f895';
        $dto->attributes = new CupEventAttributesDto();
        $dto->attributes->points = 0.9;
        $groupDistanceDto = new GroupDistancesDto();
        $groupDistanceDto->groupId = 'M_21';
        $groupDistanceDto->distances = ['b5f58bfd-1335-4e0c-8233-7dc2ab82181f', 'bb3bf8fc-929b-4769-9dad-9fc147a5b87f'];
        $dto->attributes->groupsDistances = [$groupDistanceDto];

        return new AddCupEvent($dto, AuthFaker::fakeFootprintDto());
    }
}
