<?php

declare(strict_types=1);

namespace App\Application\Dto\Club;

use OpenApi\Annotations as OpenApi;

/** @OpenApi\Schema(additionalProperties=false, required={"name"}) */
final class ClubDto
{
    public string $name;
}
