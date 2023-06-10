<?php

declare(strict_types=1);

namespace App\Domain\Shared;

final readonly class Metadata
{
    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public static function empty(): self
    {
        return new self();
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    private function __construct(
        /** @var array<string, mixed> $data */
        private array $data = [],
    ) {
    }
}
