<?php

declare(strict_types=1);

namespace Tests\Application\Service\Club;

use App\Application\Service\Club\DisableClub;
use App\Application\Service\Club\DisableClubService;
use App\Application\Service\Club\Exception\ClubNotFound;
use App\Domain\Club\ClubId;
use App\Domain\Club\ClubRepository;
use App\Domain\Shared\DummyTransactional;
use App\Domain\Shared\FrozenClock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\Club\ClubFaker;
use Tests\Faker\Shared\AuthFaker;

class DisableClubServiceTest extends TestCase
{
    private DisableClubService $service;

    private ClubRepository&MockObject $clubs;

    protected function setUp(): void
    {
        $this->service = new DisableClubService(
            $this->clubs = $this->createMock(ClubRepository::class),
            new FrozenClock(),
            new DummyTransactional(),
        );
    }

    /** @test */
    public function it_fails_when_club_not_found(): void
    {
        $this->expectException(ClubNotFound::class);

        $clubId = ClubId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');

        $this->clubs
            ->expects($this->once())
            ->method('lockById')
            ->with($this->equalTo($clubId))
            ->willReturn(null)
        ;

        $command = new DisableClub('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', AuthFaker::fakeFootprintDto());
        $this->service->execute($command);
    }

    /** @test */
    public function it_disables_club(): void
    {
        $clubId = ClubId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $club = ClubFaker::fakeClub();

        $this->clubs
            ->expects($this->once())
            ->method('lockById')
            ->with($this->equalTo($clubId))
            ->willReturn($club)
        ;

        $this->clubs
            ->expects($this->once())
            ->method('update')
            ->with($this->identicalTo($club))
        ;

        $command = new DisableClub('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', AuthFaker::fakeFootprintDto());
        $this->service->execute($command);

        $this->assertTrue($club->disabled());
    }
}
