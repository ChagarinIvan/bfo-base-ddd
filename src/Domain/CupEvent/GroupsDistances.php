<?php

declare(strict_types=1);

namespace App\Domain\CupEvent;

use function array_filter;

final readonly class GroupsDistances
{
    public function __construct(
        /** @var GroupDistances[] */
        private array $items = [],
    ) {
    }

    public function get(string $groupId): ?GroupDistances
    {
        return array_filter($this->items, static fn (GroupDistances $item): bool => $item->groupId === $groupId)[0] ?? null;
    }

    /** @return GroupDistances[] */
    public function all(): array
    {
        return $this->items;
    }
}
