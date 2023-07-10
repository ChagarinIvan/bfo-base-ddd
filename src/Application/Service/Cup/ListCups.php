<?php

declare(strict_types=1);

namespace App\Application\Service\Cup;

use App\Application\Dto\Cup\CupSearchDto;
use App\Application\Dto\Shared\Pagination;
use App\Domain\Shared\Criteria;

final readonly class ListCups
{
    public function __construct(
        private CupSearchDto $search,
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
