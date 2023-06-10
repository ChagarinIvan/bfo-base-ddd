<?php

declare(strict_types=1);

namespace App\Domain\CupEvent\Calculator;

use App\Domain\Cup\Group\CupGroup;
use App\Domain\CupEvent\CupEvent;
use App\Legacy\Models\CupEventPoint;
use function in_array;

final class EliteCupEventCalculator implements CupEventCalculator
{
    public function calculate(CupEvent $cupEvent, CupGroup $group): array
    {
        $distances = $cupEvent->groupsMap()->get($group->toString());

        $cupEventProtocolLines = $this->getGroupProtocolLines($cupEvent, $group);

        return $this->calculateLines($cupEvent, $cupEventProtocolLines)
            ->sortByDesc(static fn (CupEventPoint $cupEventResult) => $cupEventResult->points)
        ;

        return [];
    }

    protected function getGroupProtocolLines(CupEvent $cupEvent, CupGroup $group): Collection
    {
        $groupMap = $this->getGroupsMap($group);
        $mainDistance = $this->distanceService->findDistance($groupMap, $cupEvent->event_id);
        if ($mainDistance === null) {
            return new Collection();
        }
        $equalDistances = $this->distanceService->getEqualDistances($mainDistance);

        $distances = $equalDistances
            ->add($mainDistance)
            ->filter(static fn (Distance $distance) => in_array($distance->group->name, $groupMap, true))
        ;

        return $this->protocolLinesRepository->getCupEventDistancesProtocolLines($distances, $cupEvent);
    }

    protected function getGroupsMap(CupGroup $group): array
    {
        $map = [
            (new CupGroup(GroupMale::Man))->id() => self::MEN_GROUPS,
            (new CupGroup(GroupMale::Woman))->id() => self::WOMEN_GROUPS,
        ];

        return $map[$group->id()] ?? [];
    }
}
