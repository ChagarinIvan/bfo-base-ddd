<?php

declare(strict_types=1);

namespace App\Domain\Competition;

use DateTimeImmutable;

final readonly class CompetitionInfo
{
    public function __construct(
        public string $name,
        public string $description,
        public DateTimeImmutable $from,
        public DateTimeImmutable $to,
    ) {
    }
}
