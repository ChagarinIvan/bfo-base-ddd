<?php

declare(strict_types=1);

namespace App\Application\Dto\Event;

use App\Application\Dto\Shared\ImpressionDto;

final class ViewEventDto
{
    public string $id;

    public string $competitionId;

    public string $name;

    public string $description;

    public string $date;

    public ImpressionDto $created;

    public ImpressionDto $updated;
}
