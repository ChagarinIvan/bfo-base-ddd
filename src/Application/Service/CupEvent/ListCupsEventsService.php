<?php

declare(strict_types=1);

namespace App\Application\Service\CupEvent;

use App\Application\Dto\CupEvent\CupEventAssembler;
use App\Application\Dto\Shared\PaginationAdapter;
use App\Domain\CupEvent\CupEventRepository;

final readonly class ListCupsEventsService
{
    public function __construct(
        private CupEventRepository $cupsEvents,
        private CupEventAssembler $assembler,
    ) {
    }

    public function execute(ListCupsEvents $command): PaginationAdapter
    {
        return new PaginationAdapter(
            $this->cupsEvents->byCriteria($command->criteria()),
            $command->pagination(),
            $this->assembler->toViewCupEventDto(...)
        );
    }
}
