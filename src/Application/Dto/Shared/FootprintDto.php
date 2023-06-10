<?php

declare(strict_types=1);

namespace App\Application\Dto\Shared;

use OpenApi\Annotations as OpenApi;

/**
 * @OpenApi\Schema(additionalProperties=false, required={"id"})
 */
final class FootprintDto
{
    /** @OpenApi\Property(type="uuid")  */
    public string $id;
}
