<?php

declare(strict_types=1);

namespace App\Application\Dto\Club;

use App\Application\Dto\Shared\AuthAssembler;
use App\Domain\Club\Club;

final readonly class ClubAssembler
{
    public function __construct(private AuthAssembler $authAssembler)
    {
    }

    public function toViewClubDto(Club $club): ViewClubDto
    {
        $dto = new ViewClubDto();
        $dto->id = $club->id()->toString();
        $dto->name = $club->name();
        $dto->updated = $this->authAssembler->toImpressionDto($club->updated());
        $dto->created = $this->authAssembler->toImpressionDto($club->created());

        return $dto;
    }
}
