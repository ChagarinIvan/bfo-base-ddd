<?php

declare(strict_types=1);

namespace App\Application\Service\Club;

use App\Application\Dto\Club\ClubDto;
use App\Application\Dto\Shared\TokenFootprint;
use App\Domain\Club\Factory\ClubInput;
use App\Domain\Shared\Footprint;

final readonly class AddClub
{
    public function __construct(
        private ClubDto $dto,
        private TokenFootprint $footprint,
    ) {
    }

    public function clubInput(): ClubInput
    {
        return new ClubInput(
            $this->dto->name,
            $this->footprint(),
        );
    }

    private function footprint(): Footprint
    {
        return Footprint::fromArray($this->footprint->toArray());
    }
}
