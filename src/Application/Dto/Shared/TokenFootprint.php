<?php

declare(strict_types=1);

namespace App\Application\Dto\Shared;

use OpenApi\Annotations as OpenApi;

/** @OpenApi\Schema(additionalProperties=false, required={"id"}) */
final class TokenFootprint
{
    /** @OpenApi\Property(type="uuid")  */
    public string $id;

    /** @return non-empty-array<string, string> */
    public function toArray(): array
    {
        return ['id' => $this->id];
    }
}
