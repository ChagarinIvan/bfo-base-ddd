<?php

declare(strict_types=1);

namespace App\Domain\CupEvent\Calculator;

use App\Domain\CupEvent\CupEvent;

final readonly class CacheCupEventCalculator implements CupEventCalculator
{
    public function __construct(private CupEventCalculator $decorated)
    {
    }

    public function calculate(CupEvent $cupEvent): array
    {
        return $this->decorated->calculate($cupEvent);
    }
}