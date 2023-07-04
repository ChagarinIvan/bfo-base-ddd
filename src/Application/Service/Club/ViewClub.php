<?php

declare(strict_types=1);

namespace App\Application\Service\Club;

use App\Domain\Club\ClubId;

final readonly class ViewClub
{
    public function __construct(private string $id)
    {
    }

    public function id(): ClubId
    {
        return ClubId::fromString($this->id);
    }
}
