<?php

declare(strict_types=1);

namespace App\Application\Service\Cup;

use App\Application\Dto\Cup\CupAssembler;
use App\Application\Dto\Cup\ViewCupTypeDefinitionDto;
use App\Domain\Cup\CupTypeDefinitions;
use function array_map;

final readonly class ViewCupTypeDefinitionsService
{
    public function __construct(
        private CupTypeDefinitions $definitions,
        private CupAssembler $assembler,
    ) {
    }

    /** @return ViewCupTypeDefinitionDto[] */
    public function execute(): array
    {
        return array_map($this->assembler->toViewCupTypeDefinitionDto(...), $this->definitions->all());
    }
}
