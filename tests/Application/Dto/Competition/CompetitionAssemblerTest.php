<?php

declare(strict_types=1);

namespace Tests\Application\Dto\Competition;

use App\Application\Dto\Competition\CompetitionAssembler;
use App\Application\Dto\Shared\AuthAssembler;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as BaseUuid;
use Tests\Faker\Competition\CompetitionFaker;

final class CompetitionAssemblerTest extends TestCase
{
    private readonly CompetitionAssembler $assembler;

    protected function setUp(): void
    {
        $this->assembler = new CompetitionAssembler(new AuthAssembler());
    }

    /** @test */
    public function it_assembles_competition_to_dto(): void
    {
        $competition = CompetitionFaker::fakeCompetition();
        $dto = $this->assembler->toViewCompetitionDto($competition);

        $this->assertEquals('1fc7e705-ef72-47b2-ba4e-55779b02c61f', $dto->id);
        $this->assertEquals('test competition', $dto->name);
        $this->assertEquals('test competition description', $dto->description);
        $this->assertEquals('2023-01-01', $dto->from);
        $this->assertEquals('2023-01-02', $dto->to);
        $this->assertEquals('2022-01-01 00:00:00', $dto->created->at);
        $this->assertEquals(BaseUuid::NIL, $dto->created->by->id);
        $this->assertEquals('2022-01-01 00:00:00', $dto->updated->at);
        $this->assertEquals(BaseUuid::NIL, $dto->updated->by->id);
    }
}
