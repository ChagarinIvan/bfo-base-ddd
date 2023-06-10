<?php

declare(strict_types=1);

namespace App\Domain\Shared\Normalizer;

final readonly class ChainNormalizer implements Normalizer
{
    public function __construct(
        /** @var array<Normalizer> $normalizers */
        private iterable $normalizers,
    ) {
    }

    public function normalize(string $line): string
    {
        foreach ($this->normalizers as $normalizer) {
            /** @var Normalizer $normalizer */
            $line = $normalizer->normalize($line);
        }

        return $line;
    }
}
