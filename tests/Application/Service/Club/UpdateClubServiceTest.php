<?php

declare(strict_types=1);

namespace Tests\Application\Service\Club;

use App\Application\Dto\Club\ClubAssembler;
use App\Application\Dto\Club\ClubDto;
use App\Application\Dto\Shared\AuthAssembler;
use App\Application\Service\Club\Exception\ClubNotFound;
use App\Application\Service\Club\UpdateClub;
use App\Application\Service\Club\UpdateClubService;
use App\Domain\Club\ClubId;
use App\Domain\Club\ClubRepository;
use App\Domain\Club\Updater;
use App\Domain\Shared\DummyTransactional;
use App\Domain\Shared\FrozenClock;
use App\Domain\Shared\Normalizer\Normalizer;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\Club\ClubFaker;
use Tests\Faker\Shared\AuthFaker;

class UpdateClubServiceTest extends TestCase
{
    private UpdateClubService $service;

    private ClubRepository&MockObject $clubs;

    private Normalizer&MockObject $normalizer;

    protected function setUp(): void
    {
        $this->service = new UpdateClubService(
            $this->clubs = $this->createMock(ClubRepository::class),
            new Updater(
                $this->normalizer = $this->createMock(Normalizer::class),
                new FrozenClock(new DateTimeImmutable('2023-04-01')),
            ),
            new ClubAssembler(new AuthAssembler()),
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

        $dto = new ClubDto();
        $dto->name = 'test club update';

        $command = new UpdateClub('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', $dto, AuthFaker::fakeFootprintDto());
        $this->service->execute($command);
    }

    /** @test */
    public function it_updates_club_name(): void
    {
        $clubId = ClubId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $club = ClubFaker::fakeClub(id: '6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');

        $this->clubs
            ->expects($this->once())
            ->method('lockById')
            ->with($this->equalTo($clubId))
            ->willReturn($club)
        ;

        $this->clubs->expects($this->once())->method('update');
        $this->normalizer
            ->expects($this->once())
            ->method('normalize')
            ->with($this->equalTo('test club new'))
            ->willReturn('tеst club nеw')
        ;

        $dto = new ClubDto();
        $dto->name = 'test club new';

        $command = new UpdateClub('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', $dto, AuthFaker::fakeFootprintDto());
        $club = $this->service->execute($command);

        $this->assertEquals('tеst club nеw', $club->name);
        $this->assertEquals('2023-04-01 00:00:00', $club->updated->at);
    }
}
