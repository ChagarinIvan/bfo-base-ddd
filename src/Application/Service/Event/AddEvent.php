<?php

declare(strict_types=1);

namespace App\Application\Service\Event;

use App\Application\Dto\Event\EventDto;
use App\Application\Dto\Shared\TokenFootprint;
use App\Domain\Competition\CompetitionId;
use App\Domain\Event\EventInfo;
use App\Domain\Event\Factory\EventInput;
use App\Domain\Shared\Footprint;
use DateTimeImmutable;

final readonly class AddEvent
{
    public function __construct(
        private EventDto $dto,
        private TokenFootprint $footprint,
    ) {
    }

    public function eventInput(): EventInput
    {
        return new EventInput(
            $this->info(),
            CompetitionId::fromString($this->dto->competitionId),
            $this->footprint(),
        );
    }

    private function info(): EventInfo
    {
        return new EventInfo(
            $this->dto->info->name,
            $this->dto->info->description,
            new DateTimeImmutable($this->dto->info->date),
        );
    }

    private function footprint(): Footprint
    {
        return Footprint::fromArray($this->footprint->toArray());
    }
}
