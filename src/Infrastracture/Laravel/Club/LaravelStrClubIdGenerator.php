<?php

declare(strict_types=1);

namespace App\Infrastracture\Laravel\Club;

use App\Domain\Club\ClubId;
use App\Domain\Club\Factory\ClubIdGenerator;
use Illuminate\Support\Str;

final class LaravelStrClubIdGenerator implements ClubIdGenerator
{
    public function nextId(): ClubId
    {
        return new ClubId(Str::uuid());
    }
}
