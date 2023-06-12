<?php

declare(strict_types=1);

namespace App\Domain\CupEvent\Calculator;

use App\Domain\Cup\CupRepository;
use App\Domain\Cup\CupType;
use App\Domain\Cup\Group\CupGroup;
use App\Domain\CupEvent\Calculator\Exception\CupNotExist;
use App\Domain\CupEvent\CupEvent;

final readonly class StrategyCupEventCalculator implements CupEventCalculator
{
    public function __construct(private CupRepository $cups)
    {
    }

    public function calculate(CupEvent $cupEvent, CupGroup $group): array
    {
        $cupId = $cupEvent->cupId();
        $cup = $this->cups->byId($cupId) ?? throw CupNotExist::byId($cupId);

        $typeCalculator = match ($cup->type()) {
            CupType::ELITE => EliteCupEventCalculator::class,
            CupType::MASTER => MasterCupEventCalculator::class,
            CupType::SPRINT => SprintCupEventCalculator::class,
            CupType::BIKE => BikeCupEventCalculator::class,
            CupType::SKI => SkiCupEventCalculator::class,
            CupType::JUNIORS => JuniorCupEventCalculator::class,
            CupType::YOUTH => YouthCupEventCalculator::class,
        };

        /** @var CupEventCalculator $calculator */
        $calculator = new $typeCalculator();

        return $calculator->calculate($cupEvent, $group);
    }
}
