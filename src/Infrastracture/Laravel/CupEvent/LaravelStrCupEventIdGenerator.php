<?php

declare(strict_types=1);

namespace App\Infrastracture\Laravel\CupEvent;

use App\Domain\CupEvent\CupEventId;
use App\Domain\CupEvent\Factory\CupEventIdGenerator;
use Illuminate\Support\Str;

final class LaravelStrCupEventIdGenerator implements CupEventIdGenerator
{
    public function nextId(): CupEventId
    {
        return new CupEventId(Str::uuid());
    }
}
