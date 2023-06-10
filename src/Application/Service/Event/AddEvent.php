<?php

declare(strict_types=1);

namespace App\Application\Service\Event;

use App\Application\Dto\Event\EventInfoDto;
use App\Application\Dto\Shared\TokenFootprint;
use App\Domain\Competition\CompetitionId;
use App\Domain\Event\EventInfo;
use App\Domain\Event\Factory\EventInput;
use App\Domain\Shared\Footprint;
use DateTimeImmutable;

final readonly class AddEvent
{
    public function __construct(
        private EventInfoDto $dto,
        private string $competitionId,
        private TokenFootprint $footprint,
    ) {
    }

    public function eventInput(): EventInput
    {
        return new EventInput(
            $this->info(),
            CompetitionId::fromString($this->competitionId),
            $this->footprint(),
        );
    }

    private function info(): EventInfo
    {
        return new EventInfo(
            $this->dto->name,
            $this->dto->description,
            new DateTimeImmutable($this->dto->date),
        );
    }

    private function footprint(): Footprint
    {
        return Footprint::fromArray($this->footprint->toArray());
    }
}
