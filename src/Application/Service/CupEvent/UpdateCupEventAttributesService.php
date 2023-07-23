<?php

declare(strict_types=1);

namespace App\Application\Service\CupEvent;

use App\Application\Dto\CupEvent\CupEventAssembler;
use App\Application\Dto\CupEvent\ViewCupEventDto;
use App\Application\Service\CupEvent\Exception\CupEventNotFound;
use App\Domain\CupEvent\CupEventRepository;
use App\Domain\Shared\Clock;
use App\Domain\Shared\Impression;
use App\Domain\Shared\TransactionManager;

final readonly class UpdateCupEventAttributesService
{
    public function __construct(
        private CupEventRepository $cupsEvents,
        private Clock $clock,
        private CupEventAssembler $assembler,
        private TransactionManager $transactional,
    ) {
    }

    /** @throws CupEventNotFound */
    public function execute(UpdateCupEventAttributes $command): ViewCupEventDto
    {
        /** @var ViewCupEventDto $dto */
        $dto = $this->transactional->run(function () use ($command): ViewCupEventDto {
            $cupEvent = $this->cupsEvents->lockById($command->id()) ?? throw new CupEventNotFound();
            $impression = new Impression($this->clock->now(), $command->footprint());
            $cupEvent->updateAttributes($command->attributes(), $impression);
            $this->cupsEvents->update($cupEvent);

            return $this->assembler->toViewCupEventDto($cupEvent);
        });

        return $dto;
    }
}
