<?php

declare(strict_types=1);

namespace App\Application\Dto\Cup;

use App\Application\Dto\Shared\ImpressionDto;

final class ViewCupDto
{
    public string $id;

    public string $name;

    public int $eventsCount;

    public int $year;

    public string $type;

    public ImpressionDto $created;

    public ImpressionDto $updated;
}
