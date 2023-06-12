<?php

declare(strict_types=1);

namespace Tests\Application\Service\Competition;

use App\Application\Dto\Competition\CompetitionAssembler;
use App\Application\Dto\Competition\CompetitionSearchDto;
use App\Application\Dto\Competition\ViewCompetitionDto;
use App\Application\Dto\Shared\AuthAssembler;
use App\Application\Service\Competition\ListCompetitions;
use App\Application\Service\Competition\ListCompetitionsService;
use App\Domain\Competition\CompetitionRepository;
use App\Domain\Shared\Criteria;
use Illuminate\Pagination\Paginator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\Competition\CompetitionFaker;

class ListCompetitionsServiceTest extends TestCase
{
    private ListCompetitionsService $service;

    private CompetitionRepository&MockObject $competitions;

    protected function setUp(): void
    {
        $this->service = new ListCompetitionsService(
            $this->competitions = $this->createMock(CompetitionRepository::class),
            new CompetitionAssembler(new AuthAssembler()),
        );
    }

    /** @test */
    public function it_gets_paginated_list_of_competitions(): void
    {
        $competition1 = CompetitionFaker::fakeCompetition();
        $competition2 = CompetitionFaker::fakeCompetition(id: '6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');

        $this->competitions
            ->expects($this->once())
            ->method('byCriteria')
            ->with($this->equalTo(new Criteria(['page' => 2, 'perPage' => 10])))
            ->willReturn([$competition1, $competition2])
        ;

        $dto = new CompetitionSearchDto();

        $dto->page = 2;
        $dto->perPage = 10;

        $command = new ListCompetitions($dto);
        $result = $this->service->execute($command);

        $this->assertInstanceOf(Paginator::class, $result);
        $this->assertCount(2, $result);
        $this->assertEquals(10, $result->perPage());
        $this->assertEquals(2, $result->currentPage());
        $competitions = $result->items();
        $this->assertIsList($competitions);
        $this->assertContainsOnlyInstancesOf(ViewCompetitionDto::class, $competitions);
        $this->assertEquals('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', $competitions[1]->id);
    }
}
