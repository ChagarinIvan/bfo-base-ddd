<?php

declare(strict_types=1);

namespace App\Application\Dto\Competition;

use App\Application\Dto\Shared\ImpressionDto;
use DateTimeImmutable;
use OpenApi\Annotations as OpenApi;

/**
 * @OpenApi\Schema(
 *   additionalProperties=false,
 *   required={
 *     "id",
 *     "name",
 *     "description",
 *     "from",
 *     "to",
 *     "created",
 *     "updated"
 *   })
 */
final class ViewCompetitionDto
{
    /** @OpenApi\Property(type="uuid") */
    public string $id;

    public string $name;

    public string $description;

    /** @OpenApi\Property(type="date-time") */
    public DateTimeImmutable $from;

    /** @OpenApi\Property(type="date-time") */
    public DateTimeImmutable $to;

    public ImpressionDto $created;

    public ImpressionDto $updated;
}
