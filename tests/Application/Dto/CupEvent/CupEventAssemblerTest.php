<?php

declare(strict_types=1);

namespace Tests\Application\Dto\CupEvent;

use App\Application\Dto\CupEvent\CupEventAssembler;
use App\Application\Dto\Shared\AuthAssembler;
use App\Domain\CupEvent\CupEventId;
use App\Domain\CupEvent\CupEventPoints;
use App\Domain\Person\PersonId;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as BaseUuid;
use Tests\Faker\CupEvent\CupEventFaker;

class CupEventAssemblerTest extends TestCase
{
    private readonly CupEventAssembler $assembler;

    protected function setUp(): void
    {
        $this->assembler = new CupEventAssembler(new AuthAssembler());
    }

    /** @test */
    public function it_assembles_cup_event_to_dto(): void
    {
        $cupEvent = CupEventFaker::fakeCupEvent();
        $dto = $this->assembler->toViewCupEventDto($cupEvent);

        $this->assertEquals('1fc7e705-ef72-47b2-ba4e-55779b02c61f', $dto->id);
        $this->assertEquals('1efaf3e4-a661-4a68-b014-669e03d1f895', $dto->cupId);
        $this->assertEquals('56e6fb03-5869-427e-9bd3-14d8695500cf', $dto->eventId);
        $this->assertEquals('1100', $dto->points);
        $this->assertEquals('M_21', $dto->groupsDistances[0]['group_id']);
        $this->assertEquals('b5f58bfd-1335-4e0c-8233-7dc2ab82181f', $dto->groupsDistances[0]['distances'][0]);
        $this->assertEquals('bb3bf8fc-929b-4769-9dad-9fc147a5b87f', $dto->groupsDistances[0]['distances'][1]);
        $this->assertEquals('2022-01-01 00:00:00', $dto->created->at);
        $this->assertEquals(BaseUuid::NIL, $dto->created->by->id);
        $this->assertEquals('2022-01-01 00:00:00', $dto->updated->at);
        $this->assertEquals(BaseUuid::NIL, $dto->updated->by->id);
    }

    /** @test */
    public function it_assembles_cup_event_points_to_dto(): void
    {
        $points = new CupEventPoints(
            CupEventId::fromString('1fc7e705-ef72-47b2-ba4e-55779b02c61f'),
            PersonId::fromString('8f1942ad-f355-4bda-8e70-c5ac927062c8'),
            983,
        );
        $dto = $this->assembler->toViewCupEventPointsDto($points);

        $this->assertEquals('1fc7e705-ef72-47b2-ba4e-55779b02c61f', $dto->cupEventId);
        $this->assertEquals('8f1942ad-f355-4bda-8e70-c5ac927062c8', $dto->personId);
        $this->assertEquals(983, $dto->points);
    }
}
