<?php

declare(strict_types=1);

namespace App\Application\Service\Cup;

use App\Application\Dto\Cup\CupAssembler;
use App\Application\Dto\Shared\PaginationAdapter;
use App\Domain\Cup\CupRepository;

final readonly class ListCupsService
{
    public function __construct(
        private CupRepository $cups,
        private CupAssembler $assembler,
    ) {
    }

    public function execute(ListCups $command): PaginationAdapter
    {
        return new PaginationAdapter(
            $this->cups->byCriteria($command->criteria()),
            $command->pagination(),
            $this->assembler->toViewCupDto(...)
        );
    }
}
