<?php

declare(strict_types=1);

namespace App\Domain\CupEvent\Factory;

use App\Domain\CupEvent\CupEvent;
use App\Domain\CupEvent\CupEventRepository;
use App\Domain\CupEvent\Exception\CupEventAlreadyExist;
use App\Domain\Shared\Criteria;

final readonly class PreventDuplicateCupEventFactory implements CupEventFactory
{
    public function __construct(
        private CupEventFactory $decorated,
        private CupEventRepository $cupsEvents,
    ) {
    }

    public function create(CupEventInput $input): CupEvent
    {
        if ($cupEvent = $this->cupsEvents->oneByCriteria(
            new Criteria(['eventId' => $input->eventId, 'cupId' => $input->cupId])
        )) {
            throw CupEventAlreadyExist::byCupEvent($cupEvent);
        }

        return $this->decorated->create($input);
    }
}
