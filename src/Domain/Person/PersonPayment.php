<?php

declare(strict_types=1);

namespace App\Domain\Person;

use App\Domain\Shared\Impression;
use DateTimeImmutable;

final readonly class PersonPayment
{
    public function __construct(
        public readonly int $year,
        public readonly DateTimeImmutable $payedAt,
        public readonly Impression $createdAt,
    ) {
    }
}
