<?php

declare(strict_types=1);

namespace App\Domain\Event;

use DateTimeImmutable;

final readonly class EventInfo
{
    public function __construct(
        public string $name,
        public string $description,
        public DateTimeImmutable $date,
    ) {
    }
}
