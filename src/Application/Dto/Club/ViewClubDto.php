<?php

declare(strict_types=1);

namespace App\Application\Dto\Club;

use App\Application\Dto\Shared\ImpressionDto;

final class ViewClubDto
{
    public string $id;

    public string $name;

    public ImpressionDto $created;

    public ImpressionDto $updated;
}
