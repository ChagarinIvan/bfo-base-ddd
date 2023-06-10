<?php

declare(strict_types=1);

namespace App\Domain\Shared;

use App\Domain\User\UserId;

final readonly class Footprint
{
    /**
     * @param non-empty-array<string, string> $data
     */
    public static function fromArray(array $data): self
    {
        $userId = UserId::fromString($data['id']);

        return new self($userId);
    }

    public function __construct(
        public UserId $id,
    ) {
    }
}
