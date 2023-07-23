<?php

declare(strict_types=1);

namespace App\Application\Service\CupEvent;

use App\Application\Dto\CupEvent\CupEventAttributesDto;
use App\Application\Dto\CupEvent\GroupDistancesDto;
use App\Application\Dto\Shared\TokenFootprint;
use App\Domain\CupEvent\CupEventAttributes;
use App\Domain\CupEvent\CupEventId;
use App\Domain\CupEvent\GroupDistances;
use App\Domain\CupEvent\GroupsDistances;
use App\Domain\Distance\DistanceId;
use App\Domain\Shared\Footprint;
use function array_map;

final readonly class UpdateCupEventAttributes
{
    public function __construct(
        private string $id,
        private CupEventAttributesDto $dto,
        private TokenFootprint $footprint,
    ) {
    }

    public function footprint(): Footprint
    {
        return Footprint::fromArray($this->footprint->toArray());
    }

    public function id(): CupEventId
    {
        return CupEventId::fromString($this->id);
    }

    public function attributes(): CupEventAttributes
    {
        return new CupEventAttributes(
            new GroupsDistances(array_map(
                static fn (GroupDistancesDto $dto): GroupDistances => new GroupDistances(
                    $dto->groupId,
                    array_map(DistanceId::fromString(...), $dto->distances),
                ),
                $this->dto->groupsDistances,
            )),
            $this->dto->points,
        );
    }
}
