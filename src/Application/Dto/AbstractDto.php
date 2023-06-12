<?php

declare(strict_types=1);

namespace App\Application\Dto;

abstract class AbstractDto
{
    /** @return array<string, string> */
    abstract public static function validationRules(): array;

    /** @param array<string, mixed> $data */
    abstract public function fromArray(array $data): self;
}
