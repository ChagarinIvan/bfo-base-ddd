<?php

declare(strict_types=1);

namespace App\Domain\CupEvent\Calculator;

use App\Domain\Cup\CupRepository;
use App\Domain\Cup\CupType;
use App\Domain\Cup\Group\CupGroup;
use App\Domain\CupEvent\Calculator\Exception\CupNotExist;
use App\Domain\CupEvent\CupEvent;
use Illuminate\Contracts\Container\Container;

final readonly class StrategyCupEventCalculator implements CupEventCalculator
{
    public function __construct(private CupRepository $cups, private Container $container)
    {
    }

    public function calculate(CupEvent $cupEvent, CupGroup $group): array
    {
        $cupId = $cupEvent->cupId();
        $cup = $this->cups->byId($cupId) ?? throw CupNotExist::byId($cupId);

        /** @var CupEventCalculator $typeCalculator */
        $typeCalculator = match ($cup->type()) {
            CupType::ELITE => $this->container->get(EliteCupEventCalculator::class),
            CupType::MASTER => $this->container->get(MasterCupEventCalculator::class),
            CupType::SPRINT => $this->container->get(SprintCupEventCalculator::class),
            CupType::BIKE => $this->container->get(BikeCupEventCalculator::class),
            CupType::SKI => $this->container->get(SkiCupEventCalculator::class),
            CupType::JUNIORS => $this->container->get(JuniorCupEventCalculator::class),
            CupType::YOUTH => $this->container->get(YouthCupEventCalculator::class),
        };

        return $typeCalculator->calculate($cupEvent, $group);
    }
}
