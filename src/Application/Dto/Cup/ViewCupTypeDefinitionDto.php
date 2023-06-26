<?php

declare(strict_types=1);

namespace App\Application\Dto\Cup;

final class ViewCupTypeDefinitionDto
{
    public string $type;

    /** @var array<int, string> */
    public array $groups;
}
