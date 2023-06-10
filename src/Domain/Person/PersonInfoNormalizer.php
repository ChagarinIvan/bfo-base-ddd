<?php

declare(strict_types=1);

namespace App\Domain\Person;

use App\Domain\Shared\Normalizer\Normalizer;

final readonly class PersonInfoNormalizer
{
    // case
    // symbols
    // firstname
    public function __construct(private Normalizer $normalizer)
    {
    }

    public function normalize(PersonInfo $info): PersonInfo
    {
        return new PersonInfo(
            $this->normalizer->normalize($info->firstname),
            $this->normalizer->normalize($info->lastname),
            $info->yearOfBirthday
        );
    }
}
