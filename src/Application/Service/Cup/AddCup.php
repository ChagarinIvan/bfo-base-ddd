<?php

declare(strict_types=1);

namespace App\Application\Service\Cup;

use App\Application\Dto\Cup\CupDto;
use App\Application\Dto\Shared\TokenFootprint;
use App\Domain\Cup\CupInfo;
use App\Domain\Cup\CupType;
use App\Domain\Cup\Factory\CupInput;
use App\Domain\Shared\Footprint;

final readonly class AddCup
{
    public function __construct(
        private CupDto $dto,
        private TokenFootprint $footprint,
    ) {
    }

    public function cupInput(): CupInput
    {
        return new CupInput(
            new CupInfo(
                $this->dto->name,
                $this->dto->eventCounts,
                $this->dto->year,
                CupType::from($this->dto->type),
            ),
            $this->footprint(),
        );
    }

    private function footprint(): Footprint
    {
        return Footprint::fromArray($this->footprint->toArray());
    }
}
