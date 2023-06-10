<?php

declare(strict_types=1);

namespace App\Application\Service\CupEvent;

use App\Application\Dto\CupEvent\CupEventAssembler;
use App\Application\Service\CupEvent\Exception\CupEventNotFound;
use App\Application\Service\CupEvent\Exception\UnableToCalculateCupEvent;
use App\Domain\CupEvent\Calculator\CupEventCalculator;
use App\Domain\CupEvent\Calculator\Exception\CupNotExist;
use App\Domain\CupEvent\CupEventRepository;
use function array_map;

final readonly class CalculateCupEventService
{
    public function __construct(
        private CupEventRepository $cupEvents,
        private CupEventCalculator $calculator,
        private CupEventAssembler $assembler,
    ) {
    }

    /**
     * @throws CupEventNotFound
     * @throws UnableToCalculateCupEvent
     */
    public function execute(CalculateCupEvent $command): array
    {
        $cupEvent = $this->cupEvents->byId($command->id()) ?? throw new CupEventNotFound();
        try {
            $cupEventPoints = $this->calculator->calculate($cupEvent);
        } catch (CupNotExist $e) {
            throw UnableToCalculateCupEvent::dueError($e);
        }

        return array_map($this->assembler->toViewCupEventDto(...), $cupEventPoints);
    }
}
