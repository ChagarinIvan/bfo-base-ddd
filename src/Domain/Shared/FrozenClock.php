<?php

declare(strict_types=1);

namespace App\Domain\Shared;

use DateTimeImmutable;

final readonly class FrozenClock implements Clock
{
    public function __construct(private DateTimeImmutable $now = new DateTimeImmutable())
    {
    }

    public function now(): DateTimeImmutable
    {
        return $this->now;
    }
}
