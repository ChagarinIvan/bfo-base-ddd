<?php

declare(strict_types=1);

namespace App\Application\Dto\Cup;

use App\Application\Dto\Shared\AuthAssembler;
use App\Domain\Cup\Cup;
use App\Domain\Cup\CupTypeDefinition;
use App\Domain\Cup\Group\CupGroup;
use function array_map;

final readonly class CupAssembler
{
    public function __construct(private AuthAssembler $authAssembler)
    {
    }

    public function toViewCupTypeDefinitionDto(CupTypeDefinition $definition): ViewCupTypeDefinitionDto
    {
        $dto = new ViewCupTypeDefinitionDto();
        $dto->type = $definition->type->toString();
        $dto->groups = array_map(static fn (CupGroup $group) => $group->toString(), $definition->groups);

        return $dto;
    }

    public function toViewCupDto(Cup $cup): ViewCupDto
    {
        $dto = new ViewCupDto();
        $dto->id = $cup->id()->toString();
        $dto->name = $cup->name();
        $dto->eventsCount = $cup->eventsCount();
        $dto->year = $cup->year();
        $dto->type = $cup->type()->toString();
        $dto->created = $this->authAssembler->toImpressionDto($cup->created());
        $dto->updated = $this->authAssembler->toImpressionDto($cup->updated());

        return $dto;
    }
}
