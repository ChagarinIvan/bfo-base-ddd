<?php

declare(strict_types=1);

namespace Tests\Application\Dto\Competition;

use App\Application\Dto\Competition\CompetitionAssembler;
use App\Application\Dto\Shared\AuthAssembler;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as BaseUuid;
use Tests\Faker\Competition\CompetitionFaker;

class CompetitionAssemblerTest extends TestCase
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
        $viewDto = $this->assembler->toViewCompetitionDto($competition);

        $this->assertEquals('1fc7e705-ef72-47b2-ba4e-55779b02c61f', $viewDto->id);
        $this->assertEquals('test competition', $viewDto->name);
        $this->assertEquals('test competition description', $viewDto->description);
        $this->assertEquals(new DateTimeImmutable('2023-01-01'), $viewDto->from);
        $this->assertEquals(new DateTimeImmutable('2023-01-02'), $viewDto->to);
        $this->assertEquals(new DateTimeImmutable('2022-01-01'), $viewDto->created->at);
        $this->assertEquals(BaseUuid::NIL, $viewDto->created->by->id);
        $this->assertEquals(new DateTimeImmutable('2022-01-01'), $viewDto->updated->at);
        $this->assertEquals(BaseUuid::NIL, $viewDto->updated->by->id);
    }
}
