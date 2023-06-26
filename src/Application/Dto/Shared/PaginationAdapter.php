<?php

declare(strict_types=1);

namespace App\Application\Dto\Shared;

use App\Domain\Shared\ListingResult;
use function array_map;
use function count;

final class PaginationAdapter
{
    /** @var callable */
    private $action;

    public function __construct(
        private readonly ListingResult $result,
        private readonly Pagination $request,
        callable $action,
    ) {
        $this->action = $action;
    }

    /** @return array<int, mixed> */
    public function items(): array
    {
        return array_map(($this->action)(...), $this->result->items);
    }

    public function count(): int
    {
        return count($this->result->items);
    }

    public function total(): int
    {
        return $this->result->total;
    }

    public function perPage(): int
    {
        return $this->request->perPage;
    }

    public function page(): int
    {
        return $this->request->page;
    }
}
