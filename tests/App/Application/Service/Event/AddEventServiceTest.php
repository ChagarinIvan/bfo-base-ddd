<?php

declare(strict_types=1);

namespace Tests\Application\Service\Event;

use App\Application\Dto\Event\EventAssembler;
use App\Application\Dto\Event\EventInfoDto;
use App\Application\Dto\Shared\AuthAssembler;
use App\Application\Service\Event\AddEvent;
use App\Application\Service\Event\AddEventService;
use App\Domain\Competition\CompetitionId;
use App\Domain\Event\EventInfo;
use App\Domain\Event\EventRepository;
use App\Domain\Event\Factory\EventFactory;
use App\Domain\Event\Factory\EventInput;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\Event\EventFaker;
use Tests\Faker\Shared\AuthFaker;

class AddEventServiceTest extends TestCase
{
    private AddEventService $service;

    private EventFactory&MockObject $factory;

    private EventRepository&MockObject $events;

    protected function setUp(): void
    {
        $this->service = new AddEventService(
            $this->factory = $this->createMock(EventFactory::class),
            $this->events = $this->createMock(EventRepository::class),
            new EventAssembler(new AuthAssembler()),
        );
    }

    /** @test */
    public function it_creates_event(): void
    {
        $info = new EventInfo(
            'test event',
            'test event description',
            new DateTimeImmutable('2023-01-01'),
        );

        $input = new EventInput(
            $info,
            CompetitionId::fromString('1efaf3e4-a661-4a68-b014-669e03d1f895'),
            AuthFaker::fakeFootprint(),
        );

        $event = EventFaker::fakeEvent();

        $this->factory
            ->expects($this->once())
            ->method('create')
            ->with($this->equalTo($input))
            ->willReturn($event)
        ;

        $this->events
            ->expects($this->once())
            ->method('add')
            ->with($this->identicalTo($event))
        ;

        $dto = new EventInfoDto();
        $dto->name = 'test event';
        $dto->description = 'test event description';
        $dto->date = '2023-01-01';

        $command = new AddEvent($dto, '1efaf3e4-a661-4a68-b014-669e03d1f895', AuthFaker::fakeFootprintDto());
        $eventDto = $this->service->execute($command);

        $this->assertEquals($event->id()->toString(), $eventDto->id);
    }
}
