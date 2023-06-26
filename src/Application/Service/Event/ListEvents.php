<?php

declare(strict_types=1);

namespace App\Application\Service\Event;

use App\Application\Dto\Event\EventSearchDto;
use App\Application\Dto\Shared\Pagination;
use App\Domain\Shared\Criteria;

final readonly class ListEvents
{
    public function __construct(
        private EventSearchDto $search,
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
