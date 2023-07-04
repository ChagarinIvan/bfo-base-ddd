<?php

declare(strict_types=1);

namespace App\Application\Service\Club;

use App\Application\Dto\Club\ClubAssembler;
use App\Application\Dto\Shared\PaginationAdapter;
use App\Domain\Club\ClubRepository;

final readonly class ListClubsService
{
    public function __construct(
        private ClubRepository $clubs,
        private ClubAssembler $assembler,
    ) {
    }

    public function execute(ListClubs $command): PaginationAdapter
    {
        return new PaginationAdapter(
            $this->clubs->byCriteria($command->criteria()),
            $command->pagination(),
            $this->assembler->toViewClubDto(...)
        );
    }
}
