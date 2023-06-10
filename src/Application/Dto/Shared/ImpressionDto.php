<?php

declare(strict_types=1);

namespace App\Application\Dto\Shared;

use DateTimeImmutable;
use OpenApi\Annotations as OpenApi;

/**
 * @OpenApi\Schema(additionalProperties=false, required={"at", "by"})
 */
final class ImpressionDto
{
    /** @OpenApi\Property(type="string")  */
    public DateTimeImmutable $at;

    public FootprintDto $by;
}
