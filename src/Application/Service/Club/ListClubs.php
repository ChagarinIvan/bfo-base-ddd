<?php

declare(strict_types=1);

namespace App\Application\Service\Club;

use App\Application\Dto\Club\ClubSearchDto;
use App\Application\Dto\Shared\Pagination;
use App\Domain\Shared\Criteria;
use function get_object_vars;

final readonly class ListClubs
{
    public function __construct(
        private ClubSearchDto $search,
    ) {
    }

    public function criteria(): Criteria
    {
        $params = get_object_vars($this->search->pagination);
        if (isset($this->search->name)) {
            $params['name_like'] = $this->search->name;
        }

        return new Criteria($params);
    }

    public function pagination(): Pagination
    {
        return $this->search->pagination;
    }
}
