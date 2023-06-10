<?php

declare(strict_types=1);

namespace App\Domain\Shared\Normalizer;

use function mb_strtolower;

final readonly class CaseNormalizer implements Normalizer
{
    public function normalize(string $line): string
    {
        return mb_strtolower($line);
    }
}
