<?php

declare(strict_types=1);

namespace App\Application\Dto\Shared;

final class TokenFootprint
{
    public string $id;

    /** @return non-empty-array<string, string> */
    public function toArray(): array
    {
        return ['id' => $this->id];
    }
}
