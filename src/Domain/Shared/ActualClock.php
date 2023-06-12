<?php

declare(strict_types=1);

namespace App\Domain\Shared;

use DateTimeImmutable;

final class ActualClock implements Clock
{
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}
