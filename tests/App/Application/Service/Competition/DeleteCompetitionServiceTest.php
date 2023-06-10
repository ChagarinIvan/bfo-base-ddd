<?php

declare(strict_types=1);

namespace Tests\Application\Service\Competition;

use App\Application\Service\Competition\DeleteCompetition;
use App\Application\Service\Competition\DeleteCompetitionService;
use App\Application\Service\Competition\Exception\CompetitionNotFound;
use App\Domain\Competition\CompetitionId;
use App\Domain\Competition\CompetitionRepository;
use App\Domain\Shared\DummyTransactional;
use App\Domain\Shared\FrozenClock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\Competition\CompetitionFaker;
use Tests\Faker\Shared\AuthFaker;

class DeleteCompetitionServiceTest extends TestCase
{
    private DeleteCompetitionService $service;

    private CompetitionRepository&MockObject $competitions;

    protected function setUp(): void
    {
        $this->service = new DeleteCompetitionService(
            $this->competitions = $this->createMock(CompetitionRepository::class),
            new FrozenClock(),
            new DummyTransactional(),
        );
    }

    /** @test */
    public function it_fails_when_competition_not_found(): void
    {
        $this->expectException(CompetitionNotFound::class);

        $competitionId = CompetitionId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');

        $this->competitions
            ->expects($this->once())
            ->method('lockById')
            ->with($this->equalTo($competitionId))
            ->willReturn(null)
        ;

        $command = new DeleteCompetition('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', AuthFaker::fakeFootprintDto());
        $this->service->execute($command);
    }

    /** @test */
    public function it_deletes_competition(): void
    {
        $competitionId = CompetitionId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $competition = CompetitionFaker::fakeCompetition();

        $this->competitions
            ->expects($this->once())
            ->method('lockById')
            ->with($this->equalTo($competitionId))
            ->willReturn($competition)
        ;

        $this->competitions
            ->expects($this->once())
            ->method('update')
            ->with($this->identicalTo($competition))
        ;

        $command = new DeleteCompetition('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', AuthFaker::fakeFootprintDto());
        $this->service->execute($command);

        $this->assertTrue($competition->disabled());
    }
}
