<?php

declare(strict_types=1);

namespace App\Domain\Club\Factory;

use App\Domain\Club\Club;
use App\Domain\Shared\Normalizer\Normalizer;

final readonly class NormalizeClubNameClubFactory implements ClubFactory
{
    public function __construct(
        private ClubFactory $decorated,
        // symbols
        private Normalizer $normalizer,
    ) {
    }

    public function create(ClubInput $input): Club
    {
        return $this->decorated->create($input->withName($this->normalizer->normalize($input->name)));
    }
}
