<?php

declare(strict_types=1);

namespace App\Application\Dto\Cup;

use App\Domain\Cup\CupTypeDefinition;
use App\Domain\Cup\Group\CupGroup;
use function array_map;

final readonly class CupAssembler
{
    public function __construct()
    {
    }

    public function toViewCupTypeDefinitionDto(CupTypeDefinition $definition): ViewCupTypeDefinitionDto
    {
        $dto = new ViewCupTypeDefinitionDto();
        $dto->type = $definition->type->toString();
        $dto->groups = array_map(static fn (CupGroup $group) => $group->toString(), $definition->groups);

        return $dto;
    }
}
