<?php

declare(strict_types=1);

namespace App\Application\Service\Club;

use App\Application\Service\Club\Exception\ClubNotFound;
use App\Domain\Club\ClubRepository;
use App\Domain\Shared\Clock;
use App\Domain\Shared\Impression;
use App\Domain\Shared\TransactionManager;

final readonly class DisableClubService
{
    public function __construct(
        private ClubRepository $clubs,
        private Clock $clock,
        private TransactionManager $transactional,
    ) {
    }

    /** @throws ClubNotFound */
    public function execute(DisableClub $command): void
    {
        $this->transactional->run(function () use ($command): void {
            $club = $this->clubs->lockById($command->id()) ?? throw new ClubNotFound();
            $impression = new Impression($this->clock->now(), $command->footprint());
            $club->disable($impression);

            $this->clubs->update($club);
        });
    }
}
