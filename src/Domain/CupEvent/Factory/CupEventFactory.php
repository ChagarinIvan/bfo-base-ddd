<?php

declare(strict_types=1);

namespace App\Domain\CupEvent\Factory;

use App\Domain\CupEvent\CupEvent;
use App\Domain\CupEvent\Exception\CupEventAlreadyExist;

interface CupEventFactory
{
    /** @throws CupEventAlreadyExist */
    public function create(CupEventInput $input): CupEvent;
}
