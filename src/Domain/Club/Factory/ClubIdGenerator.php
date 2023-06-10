<?php

declare(strict_types=1);

namespace App\Domain\Club\Factory;

use App\Domain\Club\ClubId;

interface ClubIdGenerator
{
    public function nextId(): ClubId;
}
