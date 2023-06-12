<?php

declare(strict_types=1);

namespace Tests\Application\Service\Competition;

use App\Application\Dto\Competition\CompetitionAssembler;
use App\Application\Dto\Competition\ViewCompetitionDto;
use App\Application\Dto\Shared\AuthAssembler;
use App\Application\Service\Competition\Exception\CompetitionNotFound;
use App\Application\Service\Competition\ViewCompetition;
use App\Application\Service\Competition\ViewCompetitionService;
use App\Domain\Competition\CompetitionId;
use App\Domain\Competition\CompetitionRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\Competition\CompetitionFaker;

class ViewCompetitionServiceTest extends TestCase
{
    private ViewCompetitionService $service;

    private CompetitionRepository&MockObject $competitions;

    protected function setUp(): void
    {
        $this->service = new ViewCompetitionService(
            $this->competitions = $this->createMock(CompetitionRepository::class),
            new CompetitionAssembler(new AuthAssembler()),
        );
    }

    /** @test */
    public function it_fails_when_competition_not_found(): void
    {
        $this->expectException(CompetitionNotFound::class);

        $competitionId = CompetitionId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');

        $this->competitions
            ->expects($this->once())
            ->method('byId')
            ->with($this->equalTo($competitionId))
            ->willReturn(null)
        ;

        $command = new ViewCompetition('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $this->service->execute($command);
    }

    /** @test */
    public function it_shows_competition(): void
    {
        $competitionId = CompetitionId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $competition = CompetitionFaker::fakeCompetition(id: '6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');

        $this->competitions
            ->expects($this->once())
            ->method('byId')
            ->with($this->equalTo($competitionId))
            ->willReturn($competition)
        ;

        $command = new ViewCompetition('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $result = $this->service->execute($command);

        $this->assertInstanceOf(ViewCompetitionDto::class, $result);
        $this->assertEquals('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', $result->id);
    }
}
