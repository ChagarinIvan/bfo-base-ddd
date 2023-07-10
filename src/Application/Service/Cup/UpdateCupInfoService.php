<?php

declare(strict_types=1);

namespace App\Application\Service\Cup;

use App\Application\Dto\Cup\CupAssembler;
use App\Application\Dto\Cup\ViewCupDto;
use App\Application\Service\Cup\Exception\CupNotFound;
use App\Domain\Cup\CupRepository;
use App\Domain\Shared\Clock;
use App\Domain\Shared\Impression;
use App\Domain\Shared\TransactionManager;

final readonly class UpdateCupInfoService
{
    public function __construct(
        private CupRepository $cups,
        private Clock $clock,
        private CupAssembler $assembler,
        private TransactionManager $transactional,
    ) {
    }

    /** @throws CupNotFound( */
    public function execute(UpdateCup $command): ViewCupDto
    {
        /** @var ViewCupDto $dto */
        $dto = $this->transactional->run(function () use ($command): ViewCupDto {
            $cup = $this->cups->lockById($command->id()) ?? throw new CupNotFound();
            $impression = new Impression($this->clock->now(), $command->footprint());
            $cup->updateInfo($command->info(), $impression);
            $this->cups->update($cup);

            return $this->assembler->toViewCupDto($cup);
        });

        return $dto;
    }
}
