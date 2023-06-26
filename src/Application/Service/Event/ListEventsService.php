<?php

declare(strict_types=1);

namespace App\Application\Service\Event;

use App\Application\Dto\Event\EventAssembler;
use App\Application\Dto\Shared\PaginationAdapter;
use App\Domain\Event\EventRepository;

final readonly class ListEventsService
{
    public function __construct(
        private EventRepository $events,
        private EventAssembler $assembler,
    ) {
    }

    public function execute(ListEvents $command): PaginationAdapter
    {
        return new PaginationAdapter(
            $this->events->byCriteria($command->criteria()),
            $command->pagination(),
            $this->assembler->toViewEventDto(...)
        );
    }
}
