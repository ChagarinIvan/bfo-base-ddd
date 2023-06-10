<?php

declare(strict_types=1);

namespace App\Application\Service\Event;

use App\Application\Dto\Event\EventInfoDto;
use App\Application\Dto\Shared\TokenFootprint;
use App\Domain\Event\EventId;
use App\Domain\Event\EventInfo;
use App\Domain\Shared\Footprint;
use DateTimeImmutable;

final readonly class UpdateEventInfo
{
    public function __construct(
        private string $id,
        private EventInfoDto $dto,
        private TokenFootprint $footprint
    ) {
    }

    public function info(): EventInfo
    {
        return new EventInfo(
            $this->dto->name,
            $this->dto->description,
            new DateTimeImmutable($this->dto->date),
        );
    }

    public function footprint(): Footprint
    {
        return Footprint::fromArray($this->footprint->toArray());
    }

    public function id(): EventId
    {
        return EventId::fromString($this->id);
    }
}
