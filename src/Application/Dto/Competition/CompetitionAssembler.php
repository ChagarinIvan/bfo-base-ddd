<?php

declare(strict_types=1);

namespace App\Application\Dto\Competition;

use App\Application\Dto\Shared\AuthAssembler;
use App\Domain\Competition\Competition;

final readonly class CompetitionAssembler
{
    public function __construct(private AuthAssembler $authAssembler)
    {
    }

    public function toViewCompetitionDto(Competition $competition): ViewCompetitionDto
    {
        $dto = new ViewCompetitionDto();
        $dto->id = $competition->id()->toString();
        $dto->name = $competition->name();
        $dto->description = $competition->description();
        $dto->from = $competition->from()->format('Y-m-d');
        $dto->to = $competition->to()->format('Y-m-d');
        $dto->updated = $this->authAssembler->toImpressionDto($competition->updated());
        $dto->created = $this->authAssembler->toImpressionDto($competition->created());

        return $dto;
    }
}
