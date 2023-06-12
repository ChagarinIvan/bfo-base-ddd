<?php

declare(strict_types=1);

namespace App\Application\Service\Competition;

use App\Application\Dto\Competition\CompetitionAssembler;
use App\Domain\Competition\CompetitionRepository;
use Illuminate\Pagination\Paginator;
use function array_map;

final readonly class ListCompetitionsService
{
    public function __construct(
        private CompetitionRepository $competitions,
        private CompetitionAssembler $assembler,
    ) {
    }

    public function execute(ListCompetitions $command): Paginator
    {
        $competitions = $this->competitions->byCriteria($command->criteria());

        return new Paginator(
            array_map($this->assembler->toViewCompetitionDto(...), $competitions),
            $command->perPage(),
            $command->page(),
        );
    }
}
