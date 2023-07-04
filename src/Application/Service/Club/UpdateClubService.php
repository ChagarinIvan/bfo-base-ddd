<?php

declare(strict_types=1);

namespace App\Application\Service\Club;

use App\Application\Dto\Club\ClubAssembler;
use App\Application\Dto\Club\ViewClubDto;
use App\Application\Service\Club\Exception\ClubNotFound;
use App\Domain\Club\ClubRepository;
use App\Domain\Club\Updater;
use App\Domain\Shared\TransactionManager;

final readonly class UpdateClubService
{
    public function __construct(
        private ClubRepository $clubs,
        private Updater $updater,
        private ClubAssembler $assembler,
        private TransactionManager $transactional,
    ) {
    }

    /** @throws ClubNotFound */
    public function execute(UpdateClub $command): ViewClubDto
    {
        /** @var ViewClubDto $dto */
        $dto = $this->transactional->run(function () use ($command): ViewClubDto {
            $club = $this->clubs->lockById($command->id()) ?? throw new ClubNotFound();
            $this->updater->update($command->clubInput(), $club);
            $this->clubs->update($club);

            return $this->assembler->toViewClubDto($club);
        });

        return $dto;
    }
}
