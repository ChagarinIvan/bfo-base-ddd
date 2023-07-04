<?php

declare(strict_types=1);

namespace Tests\Application\Service\Club;

use App\Application\Dto\Club\ClubAssembler;
use App\Application\Dto\Club\ViewClubDto;
use App\Application\Dto\Shared\AuthAssembler;
use App\Application\Service\Club\Exception\ClubNotFound;
use App\Application\Service\Club\ViewClub;
use App\Application\Service\Club\ViewClubService;
use App\Domain\Club\ClubId;
use App\Domain\Club\ClubRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\Club\ClubFaker;

class ViewClubServiceTest extends TestCase
{
    private ViewClubService $service;

    private ClubRepository&MockObject $clubs;

    protected function setUp(): void
    {
        $this->service = new ViewClubService(
            $this->clubs = $this->createMock(ClubRepository::class),
            new ClubAssembler(new AuthAssembler()),
        );
    }

    /** @test */
    public function it_fails_when_club_not_found(): void
    {
        $this->expectException(ClubNotFound::class);

        $clubId = ClubId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');

        $this->clubs
            ->expects($this->once())
            ->method('byId')
            ->with($this->equalTo($clubId))
            ->willReturn(null)
        ;

        $command = new ViewClub('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $this->service->execute($command);
    }

    /** @test */
    public function it_shows_club(): void
    {
        $clubId = ClubId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $club = ClubFaker::fakeClub(id: '6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');

        $this->clubs
            ->expects($this->once())
            ->method('byId')
            ->with($this->equalTo($clubId))
            ->willReturn($club)
        ;

        $command = new ViewClub('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $result = $this->service->execute($command);

        $this->assertInstanceOf(ViewClubDto::class, $result);
        $this->assertEquals('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', $result->id);
    }
}
