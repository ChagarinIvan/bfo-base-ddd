<?php

declare(strict_types=1);

namespace App\Application\Dto\Event;

use App\Application\Dto\Shared\ImpressionDto;

/**
 * @OpenApi\Schema(
 *   additionalProperties=false,
 *   required={
 *     "id",
 *     "competitionId",
 *     "name",
 *     "description",
 *     "date",
 *     "created",
 *     "updated"
 *   })
 */
final class ViewEventDto
{
    /** @OpenApi\Property(type="uuid") */
    public string $id;

    /** @OpenApi\Property(type="uuid") */
    public string $competitionId;

    public string $name;

    public string $description;

    /** @OpenApi\Property(type="date") */
    public string $date;

    public ImpressionDto $created;

    public ImpressionDto $updated;
}
