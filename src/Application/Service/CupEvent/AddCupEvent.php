<?php

declare(strict_types=1);

namespace App\Application\Service\CupEvent;

use App\Application\Dto\CupEvent\CupEventDto;
use App\Application\Dto\CupEvent\GroupDistancesDto;
use App\Application\Dto\Shared\TokenFootprint;
use App\Domain\Cup\CupId;
use App\Domain\CupEvent\CupEventAttributes;
use App\Domain\CupEvent\Factory\CupEventInput;
use App\Domain\CupEvent\GroupDistances;
use App\Domain\CupEvent\GroupsDistances;
use App\Domain\Distance\DistanceId;
use App\Domain\Event\EventId;
use App\Domain\Shared\Footprint;
use function array_map;

final readonly class AddCupEvent
{
    public function __construct(
        private CupEventDto $dto,
        private TokenFootprint $footprint,
    ) {
    }

    public function cupEventInput(): CupEventInput
    {
        return new CupEventInput(
            CupId::fromString($this->dto->cupId),
            EventId::fromString($this->dto->eventId),
            new CupEventAttributes(
                new GroupsDistances(array_map(
                    static fn (GroupDistancesDto $dto): GroupDistances => new GroupDistances(
                        $dto->groupId,
                        array_map(DistanceId::fromString(...), $dto->distances),
                    ),
                    $this->dto->attributes->groupsDistances,
                )),
                $this->dto->attributes->points,
            ),
            $this->footprint(),
        );
    }

    private function footprint(): Footprint
    {
        return Footprint::fromArray($this->footprint->toArray());
    }
}
