<?php

declare(strict_types=1);

namespace App\Application\Dto\Competition;

use App\Application\Dto\Shared\ImpressionDto;

final class ViewCompetitionDto
{
    public string $id;

    public string $name;

    public string $description;

    public string $from;

    public string $to;

    public ImpressionDto $created;

    public ImpressionDto $updated;
}
