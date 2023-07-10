<?php

declare(strict_types=1);

namespace Tests\Application\Service\Cup;

use App\Application\Dto\Cup\CupAssembler;
use App\Application\Dto\Cup\CupSearchDto;
use App\Application\Dto\Cup\ViewCupDto;
use App\Application\Dto\Shared\AuthAssembler;
use App\Application\Dto\Shared\Pagination;
use App\Application\Dto\Shared\PaginationAdapter;
use App\Application\Service\Cup\ListCups;
use App\Application\Service\Cup\ListCupsService;
use App\Domain\Cup\CupRepository;
use App\Domain\Shared\Criteria;
use App\Domain\Shared\ListingResult;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\Cup\CupFaker;

class ListCupsServiceTest extends TestCase
{
    private ListCupsService $service;

    private CupRepository&MockObject $cups;

    protected function setUp(): void
    {
        $this->service = new ListCupsService(
            $this->cups = $this->createMock(CupRepository::class),
            new CupAssembler(new AuthAssembler()),
        );
    }

    /** @test */
    public function it_gets_paginated_list_of_cups(): void
    {
        $cup1 = CupFaker::fakeCup();
        $cup2 = CupFaker::fakeCup(id: '6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');

        $this->cups
            ->expects($this->once())
            ->method('byCriteria')
            ->with($this->equalTo(new Criteria(['page' => 2, 'perPage' => 10, 'name' => 'name', 'year' => '2023'])))
            ->willReturn(new ListingResult(3, [$cup1, $cup2]))
        ;

        $dto = new CupSearchDto();

        $dto->pagination = new Pagination();
        $dto->pagination->page = 2;
        $dto->pagination->perPage = 10;
        $dto->name = 'name';
        $dto->year = '2023';

        $command = new ListCups($dto);
        $result = $this->service->execute($command);

        $this->assertInstanceOf(PaginationAdapter::class, $result);
        $this->assertEquals(2, $result->count());
        $this->assertEquals(3, $result->total());
        $this->assertEquals(10, $result->perPage());
        $this->assertEquals(2, $result->page());
        $cups = $result->items();
        $this->assertIsList($cups);
        $this->assertContainsOnlyInstancesOf(ViewCupDto::class, $cups);
        $this->assertEquals('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', $cups[1]->id);
    }
}
