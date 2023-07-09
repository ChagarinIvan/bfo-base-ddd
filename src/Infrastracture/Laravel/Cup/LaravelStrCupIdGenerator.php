<?php

declare(strict_types=1);

namespace App\Infrastracture\Laravel\Cup;

use App\Domain\Cup\CupId;
use App\Domain\Cup\Factory\CupIdGenerator;
use Illuminate\Support\Str;

final class LaravelStrCupIdGenerator implements CupIdGenerator
{
    public function nextId(): CupId
    {
        return new CupId(Str::uuid());
    }
}
