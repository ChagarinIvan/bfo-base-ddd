<?php

declare(strict_types=1);

namespace App\Domain\CupEvent\Calculator;

use App\Domain\Cup\Group\CupGroup;
use App\Domain\CupEvent\Calculator\Exception\HasNoDistances;
use App\Domain\CupEvent\Calculator\Exception\IncompleteProtocolLine;
use App\Domain\CupEvent\CupEvent;
use App\Domain\CupEvent\CupEventId;
use App\Domain\CupEvent\CupEventPoints;
use App\Domain\CupEvent\GroupDistances;
use App\Domain\ProtocolLine\ProtocolLine;
use App\Domain\ProtocolLine\ProtocolLineId;
use App\Domain\ProtocolLine\ProtocolLineRepository;
use App\Domain\Shared\Criteria;
use DateTimeImmutable;
use function count;
use function max;
use function round;
use function usort;

final readonly class EliteCupEventCalculator implements CupEventCalculator
{
    public function __construct(private ProtocolLineRepository $lines)
    {
    }

    public function calculate(CupEvent $cupEvent, CupGroup $group): array
    {
        /** @var GroupDistances $groupDistances */
        $groupDistances = $cupEvent->groupsDistances()->get($group->id()) ?? throw HasNoDistances::byGroup($group);
        $lines = $this->lines->byCriteria(new Criteria([
            'distanceIdIn' => $groupDistances->distances,
            'payed' => true,
            'outOfCompetition' => false,
        ]));

        return $this->calculateLines($cupEvent, $lines);
    }

    /**
     * @param ProtocolLine[] $protocolLines
     * @return CupEventPoints[]
     */
    protected function calculateLines(CupEvent $cupEvent, array $protocolLines): array
    {
        $cupEventPointsList = [];
        $maxPoints = (int) $cupEvent->points();
        usort($protocolLines, $this->sortProtocolLines(...));
        $firstResult = $protocolLines[0];

        /** @var ProtocolLineId $id */
        $id = $firstResult->id();
        $personId = $firstResult->personId() ?? throw IncompleteProtocolLine::byId($id);
        $firstResultSeconds = $firstResult->time() ? $this->secondsSinceMidnight($firstResult->time()) : 0;

        /** @var CupEventId $cupEventId */
        $cupEventId = $cupEvent->id();
        $cupEventPoints = new CupEventPoints(
            $cupEventId,
            $personId,
            $firstResult->time() ? $maxPoints : 0
        );

        $cupEventPointsList[] = $cupEventPoints;
        for ($i = 1; $i < count($protocolLines); ++$i) {
            $protocolLine = $protocolLines[$i];

            $protocolLinePersonId = $protocolLine->personId();
            if ($protocolLinePersonId === null) {
                continue;
            } elseif ($protocolLine->time()) {
                $diff = $this->secondsSinceMidnight($protocolLine->time());
                $points = $diff === 0 ? 0 : (int) round($maxPoints * (2 * $firstResultSeconds / $diff - 1));
                $points = (int) max($points, 0);
            } else {
                $points = 0;
            }
            $cupEventPoints = new CupEventPoints($cupEventId, $protocolLinePersonId, $points);

            $cupEventPointsList[] = $cupEventPoints;
        }

        return $cupEventPointsList;
    }

    private function sortProtocolLines(ProtocolLine $a, ProtocolLine $b): int
    {
        $aTime = $a->time();
        $bTime = $b->time();

        if ($aTime && $bTime) {
            if ($aTime > $bTime) {
                return 1;
            } elseif ($aTime < $bTime) {
                return -1;
            } else {
                return 0;
            }
        } elseif ($aTime) {
            return -1;
        } elseif ($bTime) {
            return 1;
        } else {
            return 0;
        }
    }

    private function secondsSinceMidnight(DateTimeImmutable $time): int
    {
        $timeDiff = $time->diff(new DateTimeImmutable('today'));

        return $timeDiff->h * 3600 + $timeDiff->i * 60 + $timeDiff->s;
    }
}
