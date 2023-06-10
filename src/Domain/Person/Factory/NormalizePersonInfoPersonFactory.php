<?php

declare(strict_types=1);

namespace App\Domain\Person\Factory;

use App\Domain\Person\Person;
use App\Domain\Person\PersonInfoNormalizer;

final readonly class NormalizePersonInfoPersonFactory implements PersonFactory
{
    public function __construct(
        private PersonFactory $decorated,
        private PersonInfoNormalizer $normalizer,
    ) {
    }

    public function create(PersonInput $input): Person
    {
        return $this->decorated->create($input->withInfo($this->normalizer->normalize($input->info)));
    }
}
