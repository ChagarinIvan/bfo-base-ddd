<?php

declare(strict_types=1);

namespace Tests\Application\Service\Competition;

use App\Application\Dto\Competition\CompetitionAssembler;
use App\Application\Dto\Competition\CompetitionInfoDto;
use App\Application\Dto\Shared\AuthAssembler;
use App\Application\Service\Competition\AddCompetition;
use App\Application\Service\Competition\AddCompetitionService;
use App\Domain\Competition\CompetitionInfo;
use App\Domain\Competition\CompetitionRepository;
use App\Domain\Competition\Factory\CompetitionFactory;
use App\Domain\Competition\Factory\CompetitionInput;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\Competition\CompetitionFaker;
use Tests\Faker\Shared\AuthFaker;

class AddCompetitionServiceTest extends TestCase
{
    private AddCompetitionService $service;

    private CompetitionFactory&MockObject $factory;

    private CompetitionRepository&MockObject $competitions;

    protected function setUp(): void
    {
        $this->service = new AddCompetitionService(
            $this->factory = $this->createMock(CompetitionFactory::class),
            $this->competitions = $this->createMock(CompetitionRepository::class),
            new CompetitionAssembler(new AuthAssembler()),
        );
    }

    /** @test */
    public function it_creates_competition(): void
    {
        $info = new CompetitionInfo(
            'test competition',
            'test competition description',
            new DateTimeImmutable('2023-01-01'),
            new DateTimeImmutable('2023-01-02'),
        );

        $input = new CompetitionInput($info, AuthFaker::fakeFootprint());
        $competition = CompetitionFaker::fakeCompetition();

        $this->factory
            ->expects($this->once())
            ->method('create')
            ->with($this->equalTo($input))
            ->willReturn($competition)
        ;

        $this->competitions
            ->expects($this->once())
            ->method('add')
            ->with($this->identicalTo($competition))
        ;

        $dto = new CompetitionInfoDto();
        $dto->name = 'test competition';
        $dto->description = 'test competition description';
        $dto->from = '2023-01-01';
        $dto->to = '2023-01-02';

        $command = new AddCompetition($dto, AuthFaker::fakeFootprintDto());
        $competitionDto = $this->service->execute($command);

        $this->assertEquals($competition->id()->toString(), $competitionDto->id);
    }
}
