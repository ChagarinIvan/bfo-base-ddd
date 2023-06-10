<?php

declare(strict_types=1);

namespace App\Application\Service\Person;

use App\Application\Service\Person\Exception\PersonNotFound;
use App\Domain\Person\PersonRepository;
use App\Domain\Shared\Clock;
use App\Domain\Shared\Impression;
use App\Domain\Shared\TransactionManager;

final readonly class ChangePersonClubService
{
    public function __construct(
        private PersonRepository $persons,
        private Clock $clock,
        private TransactionManager $transactional,
    ) {
    }

    /** @throws PersonNotFound */
    public function execute(ChangePersonClub $command): void
    {
        $this->transactional->run(function () use ($command): void {
            $person = $this->persons->lockById($command->id()) ?? throw new PersonNotFound();
            $impression = new Impression($this->clock->now(), $command->footprint());
            $person->changeClub($command->clubId(), $impression);

            $this->persons->update($person);
        });
    }
}
