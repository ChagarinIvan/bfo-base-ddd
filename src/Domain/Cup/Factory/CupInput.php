<?php

declare(strict_types=1);

namespace App\Domain\Cup\Factory;

use App\Domain\Cup\CupType;
use App\Domain\Shared\Footprint;

final readonly class CupInput
{
    public function __construct(
        public string $name,
        public int $eventsCount,
        public int $year,
        public CupType $type,
        public Footprint $footprint,
    ) {
    }
}
