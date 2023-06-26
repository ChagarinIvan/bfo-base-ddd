<?php

declare(strict_types=1);

namespace App\Application\Service\Competition;

use App\Application\Dto\Competition\CompetitionAssembler;
use App\Application\Dto\Shared\PaginationAdapter;
use App\Domain\Competition\CompetitionRepository;

final readonly class ListCompetitionsService
{
    public function __construct(
        private CompetitionRepository $competitions,
        private CompetitionAssembler $assembler,
    ) {
    }

    public function execute(ListCompetitions $command): PaginationAdapter
    {
        return new PaginationAdapter(
            $this->competitions->byCriteria($command->criteria()),
            $command->pagination(),
            $this->assembler->toViewCompetitionDto(...)
        );
    }
}
