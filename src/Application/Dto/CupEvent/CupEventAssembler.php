<?php

declare(strict_types=1);

namespace App\Application\Dto\CupEvent;

use App\Domain\CupEvent\CupEventPoints;

final readonly class CupEventAssembler
{
    public function __construct()
    {
    }

    public function toViewCupEventPointsDto(CupEventPoints $points): ViewCupEventPointsDto
    {
        $dto = new ViewCupEventPointsDto();
        $dto->cupEventId = $points->cupEventId->toString();
        $dto->personId = $points->personId->toString();
        $dto->points = $points->points;

        return $dto;
    }
}
