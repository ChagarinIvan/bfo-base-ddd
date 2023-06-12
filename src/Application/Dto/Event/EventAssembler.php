<?php

declare(strict_types=1);

namespace App\Application\Dto\Event;

use App\Application\Dto\Shared\AuthAssembler;
use App\Domain\Event\Event;

final readonly class EventAssembler
{
    public function __construct(private AuthAssembler $authAssembler)
    {
    }

    public function toViewEventDto(Event $event): ViewEventDto
    {
        $dto = new ViewEventDto();
        $dto->id = $event->id()->toString();
        $dto->competitionId = $event->competitionId()->toString();
        $dto->name = $event->name();
        $dto->description = $event->description();
        $dto->date = $event->date()->format('Y-m-d');
        $dto->updated = $this->authAssembler->toImpressionDto($event->updated());
        $dto->created = $this->authAssembler->toImpressionDto($event->created());

        return $dto;
    }
}
