<?php

declare(strict_types=1);

namespace Tests\Application\Dto\CupEvent;

use App\Application\Dto\CupEvent\CupEventAssembler;
use App\Domain\CupEvent\CupEventId;
use App\Domain\CupEvent\CupEventPoints;
use App\Domain\Person\PersonId;
use PHPUnit\Framework\TestCase;

class CupEventAssemblerTest extends TestCase
{
    private readonly CupEventAssembler $assembler;

    protected function setUp(): void
    {
        $this->assembler = new CupEventAssembler();
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
