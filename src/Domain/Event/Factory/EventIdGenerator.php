<?php

declare(strict_types=1);

namespace App\Domain\Event\Factory;

use App\Domain\Event\EventId;

interface EventIdGenerator
{
    public function nextId(): EventId;
}
