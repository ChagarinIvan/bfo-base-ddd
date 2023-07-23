<?php

declare(strict_types=1);

namespace Tests\Domain\CupEvent\Factory;

use App\Domain\Cup\CupId;
use App\Domain\CupEvent\CupEventAttributes;
use App\Domain\CupEvent\CupEventRepository;
use App\Domain\CupEvent\Exception\CupEventAlreadyExist;
use App\Domain\CupEvent\Factory\CupEventFactory;
use App\Domain\CupEvent\Factory\CupEventInput;
use App\Domain\CupEvent\Factory\PreventDuplicateCupEventFactory;
use App\Domain\CupEvent\GroupsDistances;
use App\Domain\Event\EventId;
use App\Domain\Shared\Criteria;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\CupEvent\CupEventFaker;
use Tests\Faker\Shared\AuthFaker;

class PreventDuplicateCupEventFactoryTest extends TestCase
{
    private readonly CupEventFactory&MockObject $decorated;

    private readonly CupEventRepository&MockObject $cupEvents;

    private readonly PreventDuplicateCupEventFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new PreventDuplicateCupEventFactory(
            $this->decorated = $this->createMock(CupEventFactory::class),
            $this->cupEvents = $this->createMock(CupEventRepository::class),
        );
    }

    /** @test */
    public function it_fails_when_cup_event_for_cup_and_event_already_exists(): void
    {
        $this->expectException(CupEventAlreadyExist::class);
        $this->expectExceptionMessage('Cup event for cup "1efaf3e4-a661-4a68-b014-669e03d1f895" on event "56e6fb03-5869-427e-9bd3-14d8695500cf" already exist.');

        $this->decorated->expects($this->never())->method('create');
        $this->cupEvents
            ->expects($this->once())
            ->method('oneByCriteria')
            ->with($this->equalTo(new Criteria([
                'cupId' => CupId::fromString('1efaf3e4-a661-4a68-b014-669e03d1f895'),
                'eventId' => EventId::fromString('56e6fb03-5869-427e-9bd3-14d8695500cf'),
            ])))
            ->willReturn(CupEventFaker::fakeCupEvent())
        ;

        $input = $this->stubInput();

        $this->factory->create($input);
    }

    /** @test */
    public function it_propagates_cup_event_creation_on_equal_cup_event_not_exists(): void
    {
        $input = $this->stubInput();

        $this->decorated
            ->expects($this->once())
            ->method('create')
            ->with($this->identicalTo($input))
            ->willReturn(CupEventFaker::fakeCupEvent())
        ;

        $this->cupEvents
            ->expects($this->once())
            ->method('oneByCriteria')
            ->with($this->equalTo(new Criteria([
                'cupId' => CupId::fromString('1efaf3e4-a661-4a68-b014-669e03d1f895'),
                'eventId' => EventId::fromString('56e6fb03-5869-427e-9bd3-14d8695500cf'),
            ])))
            ->willReturn(null)
        ;

        $this->factory->create($input);
    }

    private function stubInput(): CupEventInput
    {
        return new CupEventInput(
            CupId::fromString('1efaf3e4-a661-4a68-b014-669e03d1f895'),
            EventId::fromString('56e6fb03-5869-427e-9bd3-14d8695500cf'),
            new CupEventAttributes(new GroupsDistances(), 1000),
            AuthFaker::fakeFootprint(),
        );
    }
}
