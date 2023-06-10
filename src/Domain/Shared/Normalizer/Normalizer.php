<?php

declare(strict_types=1);

namespace App\Domain\Shared\Normalizer;

interface Normalizer
{
    public function normalize(string $line): string;
}
