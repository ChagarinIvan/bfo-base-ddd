<?php

declare(strict_types=1);

namespace Tests\Application\Dto\Event;

use App\Application\Dto\Event\EventAssembler;
use App\Application\Dto\Shared\AuthAssembler;
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
        $dto = $this->assembler->toViewEventDto($event);

        $this->assertEquals('1fc7e705-ef72-47b2-ba4e-55779b02c61f', $dto->id);
        $this->assertEquals('test event', $dto->name);
        $this->assertEquals('1efaf3e4-a661-4a68-b014-669e03d1f895', $dto->competitionId);
        $this->assertEquals('test event description', $dto->description);
        $this->assertEquals('2023-01-01', $dto->date);
        $this->assertEquals('2022-01-01 00:00:00', $dto->created->at);
        $this->assertEquals(BaseUuid::NIL, $dto->created->by->id);
        $this->assertEquals('2022-01-01 00:00:00', $dto->updated->at);
        $this->assertEquals(BaseUuid::NIL, $dto->updated->by->id);
    }
}
