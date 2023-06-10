<?php

declare(strict_types=1);

namespace App\Infrastracture\Laravel\Event;

use App\Domain\Event\EventId;
use App\Domain\Event\Factory\EventIdGenerator;
use Illuminate\Support\Str;

final class LaravelStrEventIdGenerator implements EventIdGenerator
{
    public function nextId(): EventId
    {
        return new EventId(Str::uuid());
    }
}
