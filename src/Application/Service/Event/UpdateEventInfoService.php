<?php

declare(strict_types=1);

namespace App\Application\Service\Event;

use App\Application\Dto\Event\EventAssembler;
use App\Application\Dto\Event\ViewEventDto;
use App\Application\Service\Competition\Exception\CompetitionNotFound;
use App\Application\Service\Event\Exception\EventNotFound;
use App\Domain\Event\EventRepository;
use App\Domain\Shared\Clock;
use App\Domain\Shared\Impression;
use App\Domain\Shared\TransactionManager;

final readonly class UpdateEventInfoService
{
    public function __construct(
        private EventRepository $events,
        private Clock $clock,
        private EventAssembler $assembler,
        private TransactionManager $transactional,
    ) {
    }

    /** @throws CompetitionNotFound */
    public function execute(UpdateEventInfo $command): ViewEventDto
    {
        /** @var ViewEventDto $dto */
        $dto = $this->transactional->run(function () use ($command): ViewEventDto {
            $event = $this->events->lockById($command->id()) ?? throw new EventNotFound();
            $impression = new Impression($this->clock->now(), $command->footprint());
            $event->updateInfo($command->info(), $impression);
            $this->events->update($event);

            return $this->assembler->toViewEventDto($event);
        });

        return $dto;
    }
}
