<?php

declare(strict_types=1);

namespace Tests\Application\Service\CupEvent;

use App\Application\Dto\CupEvent\CupEventAssembler;
use App\Application\Dto\CupEvent\CupEventAttributesDto;
use App\Application\Dto\CupEvent\GroupDistancesDto;
use App\Application\Dto\Shared\AuthAssembler;
use App\Application\Service\CupEvent\Exception\CupEventNotFound;
use App\Application\Service\CupEvent\UpdateCupEventAttributes;
use App\Application\Service\CupEvent\UpdateCupEventAttributesService;
use App\Domain\CupEvent\CupEventId;
use App\Domain\CupEvent\CupEventRepository;
use App\Domain\Shared\DummyTransactional;
use App\Domain\Shared\FrozenClock;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\CupEvent\CupEventFaker;
use Tests\Faker\Shared\AuthFaker;

class UpdateCupEventAttributesServiceTest extends TestCase
{
    private UpdateCupEventAttributesService $service;

    private CupEventRepository&MockObject $cupsEvents;

    protected function setUp(): void
    {
        $this->service = new UpdateCupEventAttributesService(
            $this->cupsEvents = $this->createMock(CupEventRepository::class),
            new FrozenClock(new DateTimeImmutable('2023-04-01')),
            new CupEventAssembler(new AuthAssembler()),
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

        $command = new UpdateCupEventAttributes(
            '6deb7e98-b55d-4214-98ac-fc3a16e3ec6b',
            $this->stubDto(),
            AuthFaker::fakeFootprintDto(),
        );

        $this->service->execute($command);
    }

    /** @test */
    public function it_updates_cup_event_info(): void
    {
        $cupEventId = CupEventId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $cupEvent = CupEventFaker::fakeCupEvent(id: '6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');

        $this->cupsEvents
            ->expects($this->once())
            ->method('lockById')
            ->with($this->equalTo($cupEventId))
            ->willReturn($cupEvent)
        ;

        $this->cupsEvents->expects($this->once())->method('update');

        $command = new UpdateCupEventAttributes(
            '6deb7e98-b55d-4214-98ac-fc3a16e3ec6b',
            $this->stubDto(),
            AuthFaker::fakeFootprintDto()
        );

        $event = $this->service->execute($command);

        $this->assertEquals('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', $event->id);
        $this->assertEquals('1efaf3e4-a661-4a68-b014-669e03d1f895', $event->cupId);
        $this->assertEquals('56e6fb03-5869-427e-9bd3-14d8695500cf', $event->eventId);
        $this->assertEquals('0.9', $event->points);
        $this->assertEquals('M_21', $event->groupsDistances[0]['group_id']);
        $this->assertEquals('56e6fb03-5869-427e-9bd3-14d8695500cf', $event->groupsDistances[0]['distances'][0]);
        $this->assertEquals('2023-04-01 00:00:00', $event->updated->at);
    }

    private function stubDto(): CupEventAttributesDto
    {
        $attributes = new CupEventAttributesDto();

        $groupDistanceDto = new GroupDistancesDto();
        $groupDistanceDto->groupId = 'M_21';
        $groupDistanceDto->distances = ['56e6fb03-5869-427e-9bd3-14d8695500cf'];

        $attributes->points = 0.9;
        $attributes->groupsDistances = [$groupDistanceDto];

        return $attributes;
    }
}
