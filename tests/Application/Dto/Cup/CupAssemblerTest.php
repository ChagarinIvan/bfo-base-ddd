<?php

declare(strict_types=1);

namespace Tests\Application\Dto\Cup;

use App\Application\Dto\Cup\CupAssembler;
use App\Application\Dto\Shared\AuthAssembler;
use App\Domain\Cup\CupType;
use App\Domain\Cup\CupTypeDefinition;
use App\Domain\Cup\Group\CupGroup;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as BaseUuid;
use Tests\Faker\Cup\CupFaker;

class CupAssemblerTest extends TestCase
{
    private readonly CupAssembler $assembler;

    protected function setUp(): void
    {
        $this->assembler = new CupAssembler(new AuthAssembler());
    }

    /** @test */
    public function it_assembles_cup_definition_to_dto(): void
    {
        $definition = new CupTypeDefinition(CupType::ELITE, [CupGroup::fromString('M_21')]);
        $dto = $this->assembler->toViewCupTypeDefinitionDto($definition);

        $this->assertEquals('elite', $dto->type);
        $this->assertCount(1, $dto->groups);
        $this->assertEquals('M_21', $dto->groups[0]);
    }

    /** @test */
    public function it_assembles_cup_to_view_dto(): void
    {
        $cup = CupFaker::fakeCup();
        $dto = $this->assembler->toViewCupDto($cup);

        $this->assertEquals('1fc7e705-ef72-47b2-ba4e-55779b02c61f', $dto->id);
        $this->assertEquals('cup name', $dto->name);
        $this->assertEquals(3, $dto->eventsCount);
        $this->assertEquals(2023, $dto->year);
        $this->assertEquals('elite', $dto->type);
        $this->assertEquals('2022-01-01 00:00:00', $dto->created->at);
        $this->assertEquals(BaseUuid::NIL, $dto->created->by->id);
        $this->assertEquals('2022-01-01 00:00:00', $dto->updated->at);
        $this->assertEquals(BaseUuid::NIL, $dto->updated->by->id);
    }
}
