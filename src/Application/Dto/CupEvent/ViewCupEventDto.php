<?php

declare(strict_types=1);

namespace App\Application\Dto\CupEvent;

use App\Application\Dto\Shared\ImpressionDto;

final class ViewCupEventDto
{
    public string $id;

    public string $cupId;

    public string $eventId;

    public string $points;

    /** @var array<int, array<string, string|string[]>> */
    public array $groupsDistances;

    public ImpressionDto $created;

    public ImpressionDto $updated;
}
