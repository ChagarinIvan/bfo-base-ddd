<?php

declare(strict_types=1);

namespace App\Domain\Cup\Factory;

use App\Domain\Cup\CupInfo;
use App\Domain\Shared\Footprint;

final readonly class CupInput
{
    public function __construct(
        public CupInfo $info,
        public Footprint $footprint,
    ) {
    }
}
