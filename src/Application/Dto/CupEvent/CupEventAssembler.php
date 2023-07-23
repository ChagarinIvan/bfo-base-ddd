<?php

declare(strict_types=1);

namespace App\Application\Dto\CupEvent;

use App\Application\Dto\Shared\AuthAssembler;
use App\Domain\CupEvent\CupEvent;
use App\Domain\CupEvent\CupEventPoints;
use App\Domain\CupEvent\GroupDistances;
use App\Domain\CupEvent\GroupsDistances;
use App\Domain\Distance\DistanceId;
use function array_map;

final readonly class CupEventAssembler
{
    /** @return array<int, array<string, array<string>|string>> */
    public static function groupsDistancesToArray(GroupsDistances $groupsDistances): array
    {
        return array_map(static fn (GroupDistances $item): array => [
            'group_id' => $item->groupId,
            'distances' => array_map(static fn (DistanceId $id): string => $id->toString(), $item->distances),
        ], $groupsDistances->all());
    }

    public function __construct(private AuthAssembler $authAssembler)
    {
    }

    public function toViewCupEventDto(CupEvent $cupEvent): ViewCupEventDto
    {
        $dto = new ViewCupEventDto();
        $dto->id = $cupEvent->id()->toString();
        $dto->cupId = $cupEvent->cupId()->toString();
        $dto->eventId = $cupEvent->eventId()->toString();
        $dto->points = (string) $cupEvent->points();
        $dto->groupsDistances = self::groupsDistancesToArray($cupEvent->groupsDistances());
        $dto->created = $this->authAssembler->toImpressionDto($cupEvent->created());
        $dto->updated = $this->authAssembler->toImpressionDto($cupEvent->updated());

        return $dto;
    }

    public function toViewCupEventPointsDto(CupEventPoints $points): ViewCupEventPointsDto
    {
        $dto = new ViewCupEventPointsDto();
        $dto->cupEventId = $points->cupEventId->toString();
        $dto->personId = $points->personId->toString();
        $dto->points = $points->points;

        return $dto;
    }
}
