<?php

declare(strict_types=1);

namespace App\Application\Service\CupEvent;

use App\Application\Dto\CupEvent\CupEventAssembler;
use App\Application\Dto\CupEvent\ViewCupEventDto;
use App\Application\Service\CupEvent\Exception\FailedToAddCupEvent;
use App\Domain\CupEvent\CupEventRepository;
use App\Domain\CupEvent\Exception\CupEventAlreadyExist;
use App\Domain\CupEvent\Factory\CupEventFactory;

final readonly class AddCupEventService
{
    public function __construct(
        private CupEventFactory $factory,
        private CupEventRepository $cupsEvents,
        private CupEventAssembler $assembler,
    ) {
    }

    /** @throws FailedToAddCupEvent */
    public function execute(AddCupEvent $command): ViewCupEventDto
    {
        try {
            $cupEvent = $this->factory->create($command->cupEventInput());
        } catch (CupEventAlreadyExist $e) {
            throw FailedToAddCupEvent::dueError($e);
        }

        $this->cupsEvents->add($cupEvent);

        return $this->assembler->toViewCupEventDto($cupEvent);
    }
}
