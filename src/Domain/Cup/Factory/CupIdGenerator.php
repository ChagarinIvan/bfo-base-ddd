<?php

declare(strict_types=1);

namespace App\Domain\Cup\Factory;

use App\Domain\Cup\CupId;

interface CupIdGenerator
{
    public function nextId(): CupId;
}
