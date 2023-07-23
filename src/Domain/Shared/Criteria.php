<?php

declare(strict_types=1);

namespace App\Domain\Shared;

use OutOfRangeException;
use function array_diff_key;
use function array_flip;

readonly class Criteria
{
    public function __construct(
        /** @var array<string, mixed> $params */
        private array $params,
    ) {
    }

    public function hasParam(string $param): bool
    {
        return isset($this->params[$param]);
    }

    /** @return array<string, mixed> */
    public function params(): array
    {
        return $this->params;
    }

    /** @throws OutOfRangeException */
    public function param(string $key): mixed
    {
        return $this->params[$key] ?? new OutOfRangeException('Has no param.');
    }

    /**
     * @param string[] $params
     * @return array<string, mixed>
     */
    public function withoutParams(array $params): array
    {
        return array_diff_key($this->params, array_flip($params));
    }
}
