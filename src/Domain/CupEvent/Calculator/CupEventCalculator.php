<?php

declare(strict_types=1);

namespace App\Domain\CupEvent\Calculator;

use App\Domain\Cup\Group\CupGroup;
use App\Domain\CupEvent\Calculator\Exception\CupNotExist;
use App\Domain\CupEvent\CupEvent;
use App\Domain\CupEvent\CupEventPoints;

interface CupEventCalculator
{
    /**
     * @throws CupNotExist
     *
     * @return CupEventPoints[]
     */
    public function calculate(CupEvent $cupEvent, CupGroup $group): array;
}
