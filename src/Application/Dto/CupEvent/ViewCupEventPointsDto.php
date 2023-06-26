<?php

declare(strict_types=1);

namespace App\Application\Dto\CupEvent;

final class ViewCupEventPointsDto
{
    public string $cupEventId;

    public string $personId;

    public int $points;
}
