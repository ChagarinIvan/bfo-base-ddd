<?php

declare(strict_types=1);

namespace App\Application\Dto\Shared;

use OpenApi\Annotations as OpenApi;

/**
 * @OpenApi\Schema(additionalProperties=false, required={"at", "by"})
 */
final class ImpressionDto
{
    /** @OpenApi\Property(type="date-time")  */
    public string $at;

    public FootprintDto $by;
}
