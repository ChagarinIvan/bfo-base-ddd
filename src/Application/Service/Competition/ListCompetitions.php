<?php

declare(strict_types=1);

namespace App\Application\Service\Competition;

use App\Application\Dto\Competition\CompetitionSearchDto;
use App\Application\Dto\Shared\Pagination;
use App\Domain\Shared\Criteria;

final readonly class ListCompetitions
{
    public function __construct(
        private CompetitionSearchDto $search,
    ) {
    }

    public function criteria(): Criteria
    {
        return new Criteria($this->search->toArray());
    }

    public function pagination(): Pagination
    {
        return $this->search->pagination;
    }
}
