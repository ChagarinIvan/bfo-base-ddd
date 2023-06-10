<?php

declare(strict_types=1);

namespace App\Domain\Shared;

use DateTimeImmutable;

final readonly class Impression
{
    public function __construct(
        public DateTimeImmutable $at,
        public Footprint $by,
    ) {
    }
}
