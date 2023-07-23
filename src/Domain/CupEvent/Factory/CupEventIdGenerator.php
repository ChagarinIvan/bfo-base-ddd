<?php

declare(strict_types=1);

namespace App\Domain\CupEvent\Factory;

use App\Domain\CupEvent\CupEventId;

interface CupEventIdGenerator
{
    public function nextId(): CupEventId;
}
