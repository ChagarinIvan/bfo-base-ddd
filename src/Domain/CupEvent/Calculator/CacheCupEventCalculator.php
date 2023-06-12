<?php

declare(strict_types=1);

namespace App\Domain\CupEvent\Calculator;

use App\Domain\Cup\Group\CupGroup;
use App\Domain\CupEvent\CupEvent;

final readonly class CacheCupEventCalculator implements CupEventCalculator
{
    public function __construct(private CupEventCalculator $decorated)
    {
    }

    public function calculate(CupEvent $cupEvent, CupGroup $group): array
    {
        return $this->decorated->calculate($cupEvent, $group);
    }
}
