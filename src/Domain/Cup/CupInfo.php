<?php

declare(strict_types=1);

namespace App\Domain\Cup;

final readonly class CupInfo
{
    public function __construct(
        public string $name,
        public int $eventsCount,
        public int $year,
        public CupType $type,
    ) {
    }
}
