<?php

declare(strict_types=1);

namespace Tests\Application\Service\Event;

use App\Application\Dto\Event\EventAssembler;
use App\Application\Dto\Event\EventSearchDto;
use App\Application\Dto\Event\ViewEventDto;
use App\Application\Dto\Shared\AuthAssembler;
use App\Application\Dto\Shared\Pagination;
use App\Application\Dto\Shared\PaginationAdapter;
use App\Application\Service\Event\ListEvents;
use App\Application\Service\Event\ListEventsService;
use App\Domain\Event\EventRepository;
use App\Domain\Shared\Criteria;
use App\Domain\Shared\ListingResult;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\Event\EventFaker;

class ListEventsServiceTest extends TestCase
{
    private ListEventsService $service;

    private EventRepository&MockObject $events;

    protected function setUp(): void
    {
        $this->service = new ListEventsService(
            $this->events = $this->createMock(EventRepository::class),
            new EventAssembler(new AuthAssembler()),
        );
    }

    /** @test */
    public function it_gets_paginated_list_of_events(): void
    {
        $competition1 = EventFaker::fakeEvent();
        $competition2 = EventFaker::fakeEvent(id: '6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');

        $this->events
            ->expects($this->once())
            ->method('byCriteria')
            ->with($this->equalTo(new Criteria(['page' => 2, 'perPage' => 10, 'name' => 'Cup'])))
            ->willReturn(new ListingResult(3, [$competition1, $competition2]))
        ;

        $dto = new EventSearchDto();

        $dto->pagination = new Pagination();
        $dto->pagination->page = 2;
        $dto->pagination->perPage = 10;
        $dto->name = 'Cup';

        $command = new ListEvents($dto);
        $result = $this->service->execute($command);

        $this->assertInstanceOf(PaginationAdapter::class, $result);
        $this->assertEquals(2, $result->count());
        $this->assertEquals(3, $result->total());
        $this->assertEquals(10, $result->perPage());
        $this->assertEquals(2, $result->page());
        $events = $result->items();
        $this->assertIsList($events);
        $this->assertContainsOnlyInstancesOf(ViewEventDto::class, $events);
        $this->assertEquals('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', $events[1]->id);
    }
}
