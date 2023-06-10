<?php

declare(strict_types=1);

namespace Tests\Application\Service\Competition;

use App\Application\Dto\Competition\CompetitionAssembler;
use App\Application\Dto\Competition\CompetitionInfoDto;
use App\Application\Dto\Shared\AuthAssembler;
use App\Application\Service\Competition\Exception\CompetitionNotFound;
use App\Application\Service\Competition\UpdateCompetitionInfo;
use App\Application\Service\Competition\UpdateCompetitionInfoService;
use App\Domain\Competition\CompetitionId;
use App\Domain\Competition\CompetitionRepository;
use App\Domain\Shared\DummyTransactional;
use App\Domain\Shared\FrozenClock;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\Competition\CompetitionFaker;
use Tests\Faker\Shared\AuthFaker;

class UpdateCompetitionInfoServiceTest extends TestCase
{
    private UpdateCompetitionInfoService $service;

    private CompetitionRepository&MockObject $competitions;

    protected function setUp(): void
    {
        $this->service = new UpdateCompetitionInfoService(
            $this->competitions = $this->createMock(CompetitionRepository::class),
            new FrozenClock(new DateTimeImmutable('2023-04-01')),
            new CompetitionAssembler(new AuthAssembler()),
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

        $info = new CompetitionInfoDto();
        $info->name = 'test competition';
        $info->description = 'test competition';
        $info->from = '2023-01-01';
        $info->to = '2023-01-02';

        $command = new UpdateCompetitionInfo('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', $info, AuthFaker::fakeFootprintDto());
        $this->service->execute($command);
    }

    /** @test */
    public function it_updates_competition_info(): void
    {
        $competitionId = CompetitionId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $competition = CompetitionFaker::fakeCompetition(id: '6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');

        $this->competitions
            ->expects($this->once())
            ->method('lockById')
            ->with($this->equalTo($competitionId))
            ->willReturn($competition)
        ;

        $this->competitions->expects($this->once())->method('update');

        $info = new CompetitionInfoDto();
        $info->name = 'test competition new';
        $info->description = 'test competition';
        $info->from = '2023-02-02';
        $info->to = '2023-02-03';

        $command = new UpdateCompetitionInfo('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', $info, AuthFaker::fakeFootprintDto());
        $competition = $this->service->execute($command);

        $this->assertEquals('test competition new', $competition->name);
        $this->assertEquals('test competition', $competition->description);
        $this->assertEquals(new DateTimeImmutable('2023-02-02'), $competition->from);
        $this->assertEquals(new DateTimeImmutable('2023-02-03'), $competition->to);
        $this->assertEquals(new DateTimeImmutable('2023-04-01'), $competition->updated->at);
    }
}
