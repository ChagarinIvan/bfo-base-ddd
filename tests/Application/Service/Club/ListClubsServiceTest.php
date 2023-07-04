<?php

declare(strict_types=1);

namespace Tests\Application\Service\Club;

use App\Application\Dto\Club\ClubAssembler;
use App\Application\Dto\Club\ClubSearchDto;
use App\Application\Dto\Club\ViewClubDto;
use App\Application\Dto\Shared\AuthAssembler;
use App\Application\Dto\Shared\Pagination;
use App\Application\Dto\Shared\PaginationAdapter;
use App\Application\Service\Club\ListClubs;
use App\Application\Service\Club\ListClubsService;
use App\Domain\Club\ClubRepository;
use App\Domain\Shared\Criteria;
use App\Domain\Shared\ListingResult;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\Club\ClubFaker;

class ListClubsServiceTest extends TestCase
{
    private ListClubsService $service;

    private ClubRepository&MockObject $clubs;

    protected function setUp(): void
    {
        $this->service = new ListClubsService(
            $this->clubs = $this->createMock(ClubRepository::class),
            new ClubAssembler(new AuthAssembler()),
        );
    }

    /** @test */
    public function it_gets_paginated_list_of_competitions(): void
    {
        $club1 = ClubFaker::fakeClub();
        $club2 = ClubFaker::fakeClub(id: '6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');

        $this->clubs
            ->expects($this->once())
            ->method('byCriteria')
            ->with($this->equalTo(new Criteria(['page' => 2, 'perPage' => 10])))
            ->willReturn(new ListingResult(3, [$club1, $club2]))
        ;

        $dto = new ClubSearchDto();

        $dto->pagination = new Pagination();
        $dto->pagination->page = 2;
        $dto->pagination->perPage = 10;

        $command = new ListClubs($dto);
        $result = $this->service->execute($command);

        $this->assertInstanceOf(PaginationAdapter::class, $result);
        $this->assertEquals(2, $result->count());
        $this->assertEquals(3, $result->total());
        $this->assertEquals(10, $result->perPage());
        $this->assertEquals(2, $result->page());
        $clubs = $result->items();
        $this->assertIsList($clubs);
        $this->assertContainsOnlyInstancesOf(ViewClubDto::class, $clubs);
        $this->assertEquals('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', $clubs[1]->id);
    }
}
