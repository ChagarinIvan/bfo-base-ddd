<?php

declare(strict_types=1);

namespace App\Application\Dto\Club;

use App\Application\Dto\Shared\ImpressionDto;

/**
 * @OpenApi\Schema(
 *   additionalProperties=false,
 *   required={
 *     "id",
 *     "name",
 *     "created",
 *     "updated"
 *   })
 */
final class ViewClubDto
{
    /** @OpenApi\Property(type="uuid") */
    public string $id;

    public string $name;

    public ImpressionDto $created;

    public ImpressionDto $updated;
}
