<?php

declare(strict_types=1);

namespace Tests\Application\Dto\Club;

use App\Application\Dto\Club\ClubAssembler;
use App\Application\Dto\Shared\AuthAssembler;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as BaseUuid;
use Tests\Faker\Club\ClubFaker;

final class ClubAssemblerTest extends TestCase
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
        $dto = $this->assembler->toViewClubDto($club);

        $this->assertEquals('1fc7e705-ef72-47b2-ba4e-55779b02c61f', $dto->id);
        $this->assertEquals('tеst сlub', $dto->name);
        $this->assertEquals('2022-01-01 00:00:00', $dto->created->at);
        $this->assertEquals(BaseUuid::NIL, $dto->created->by->id);
        $this->assertEquals('2022-01-01 00:00:00', $dto->updated->at);
        $this->assertEquals(BaseUuid::NIL, $dto->updated->by->id);
    }
}
