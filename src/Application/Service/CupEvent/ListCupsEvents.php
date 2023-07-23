<?php

declare(strict_types=1);

namespace App\Application\Service\CupEvent;

use App\Application\Dto\CupEvent\CupEventSearchDto;
use App\Application\Dto\Shared\Pagination;
use App\Domain\Shared\Criteria;

final readonly class ListCupsEvents
{
    public function __construct(
        private CupEventSearchDto $search,
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
