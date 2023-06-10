<?php

declare(strict_types=1);

namespace App\Domain\Person;

final readonly class PersonInfo
{
    public function __construct(
        public string $firstname,
        public string $lastname,
        public ?int $yearOfBirthday = null,
    ) {
    }
}
