<?php

declare(strict_types=1);

namespace App\Application\Dto\CupEvent;

/**
 * @OpenApi\Schema(
 *   additionalProperties=false,
 *   required={
 *     "cupEventId",
 *     "personId",
 *     "points"
 *   })
 */
final class ViewCupEventPointsDto
{
    /** @OpenApi\Property(type="uuid") */
    public string $cupEventId;

    /** @OpenApi\Property(type="uuid") */
    public string $personId;

    public int $points;
}
