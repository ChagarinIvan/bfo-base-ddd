<?php

declare(strict_types=1);

namespace Tests\Application\Dto\Club;

use App\Application\Dto\Club\ClubAssembler;
use App\Application\Dto\Shared\AuthAssembler;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as BaseUuid;
use Tests\Faker\Club\ClubFaker;

class ClubAssemblerTest extends TestCase
{
    private readonly ClubAssembler $assembler;

    protected function setUp(): void
    {
        $this->assembler = new ClubAssembler(new AuthAssembler());
    }

    /** @test */
    public function it_assembles_club_to_dto(): void
    {
        $club = ClubFaker::fakeClub();
        $viewDto = $this->assembler->toViewClubDto($club);

        $this->assertEquals('1fc7e705-ef72-47b2-ba4e-55779b02c61f', $viewDto->id);
        $this->assertEquals('test club', $viewDto->name);
        $this->assertEquals(new DateTimeImmutable('2022-01-01'), $viewDto->created->at);
        $this->assertEquals(BaseUuid::NIL, $viewDto->created->by->id);
        $this->assertEquals(new DateTimeImmutable('2022-01-01'), $viewDto->updated->at);
        $this->assertEquals(BaseUuid::NIL, $viewDto->updated->by->id);
    }
}
