<?php

declare(strict_types=1);

namespace App\Application\Dto\Competition;

use OpenApi\Annotations as OpenApi;

/**
 * @OpenApi\Schema(
 *   additionalProperties=false,
 *   required={
 *     "name",
 *     "description",
 *     "from",
 *     "to",
 *   })
 */
final class CompetitionInfoDto
{
    public string $name;

    public string $description;

    /** @OpenApi\Property(type="date-time") */
    public string $from;

    /** @OpenApi\Property(type="date-time") */
    public string $to;
}
