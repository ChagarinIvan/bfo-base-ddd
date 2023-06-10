<?php

declare(strict_types=1);

namespace App\Domain\Shared;

final readonly class Criteria
{
    public function __construct(
        /** @var array<string, mixed> $params */
        private array $params,
    ) {
    }

    public function has(string $param): bool
    {
        return isset($this->params[$param]);
    }

    /** @return array<string, mixed> */
    public function params(): array
    {
        return $this->params;
    }
}
