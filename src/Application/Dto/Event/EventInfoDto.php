<?php

declare(strict_types=1);

namespace App\Application\Dto\Event;

use OpenApi\Annotations as OpenApi;

/**
 * @OpenApi\Schema(
 *   additionalProperties=false,
 *   required={
 *     "name",
 *     "description",
 *     "date",
 *   })
 */
final class EventInfoDto
{
    public string $name;

    public string $description;

    /** @OpenApi\Property(type="date-time") */
    public string $date;
}
