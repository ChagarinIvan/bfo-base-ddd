<?php

declare(strict_types=1);

namespace Tests\Application\Dto\Event;

use App\Application\Dto\Event\EventAssembler;
use App\Application\Dto\Shared\AuthAssembler;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as BaseUuid;
use Tests\Faker\Event\EventFaker;

class EventAssemblerTest extends TestCase
{
    private readonly EventAssembler $assembler;

    protected function setUp(): void
    {
        $this->assembler = new EventAssembler(new AuthAssembler());
    }

    /** @test */
    public function it_assembles_event_to_dto(): void
    {
        $event = EventFaker::fakeEvent();
        $eventDto = $this->assembler->toViewEventDto($event);

        $this->assertEquals('1fc7e705-ef72-47b2-ba4e-55779b02c61f', $eventDto->id);
        $this->assertEquals('test event', $eventDto->name);
        $this->assertEquals('1efaf3e4-a661-4a68-b014-669e03d1f895', $eventDto->competitionId);
        $this->assertEquals('test event description', $eventDto->description);
        $this->assertEquals(new DateTimeImmutable('2023-01-01'), $eventDto->date);
        $this->assertEquals(new DateTimeImmutable('2022-01-01'), $eventDto->created->at);
        $this->assertEquals(BaseUuid::NIL, $eventDto->created->by->id);
        $this->assertEquals(new DateTimeImmutable('2022-01-01'), $eventDto->updated->at);
        $this->assertEquals(BaseUuid::NIL, $eventDto->updated->by->id);
    }
}
