<?php

declare(strict_types=1);

namespace App\Domain\Club\Factory;

use App\Domain\Shared\Footprint;

final readonly class ClubInput
{
    public function __construct(
        public string $name,
        public Footprint $footprint,
    ) {
    }

    public function withName(string $name): self
    {
        return new self($name, $this->footprint);
    }
}
