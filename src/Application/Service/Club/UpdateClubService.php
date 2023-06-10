<?php

declare(strict_types=1);

namespace App\Application\Service\Club;

use App\Application\Dto\Club\ClubAssembler;
use App\Application\Dto\Club\ViewClubDto;
use App\Application\Service\Club\Exception\ClubNotFound;
use App\Domain\Club\ClubRepository;
use App\Domain\Shared\Clock;
use App\Domain\Shared\Impression;
use App\Domain\Shared\TransactionManager;

final readonly class UpdateClubService
{
    public function __construct(
        private ClubRepository $clubs,
        private Clock $clock,
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
            $impression = new Impression($this->clock->now(), $command->footprint());
            $club->update($command->input(), $impression);
            $this->clubs->update($club);

            return $this->assembler->toViewClubDto($club);
        });

        return $dto;
    }
}
