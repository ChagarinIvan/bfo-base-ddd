<?php

declare(strict_types=1);

namespace Tests\Application\Dto\Cup;

use App\Application\Dto\Cup\CupAssembler;
use App\Domain\Cup\CupType;
use App\Domain\Cup\CupTypeDefinition;
use App\Domain\Cup\Group\CupGroup;
use PHPUnit\Framework\TestCase;

class CupAssemblerTest extends TestCase
{
    private readonly CupAssembler $assembler;

    protected function setUp(): void
    {
        $this->assembler = new CupAssembler();
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
}
