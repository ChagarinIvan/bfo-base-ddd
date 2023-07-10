<?php

declare(strict_types=1);

namespace App\Application\Service\Cup;

use App\Application\Dto\Cup\CupDto;
use App\Application\Dto\Shared\TokenFootprint;
use App\Domain\Cup\CupId;
use App\Domain\Cup\CupInfo;
use App\Domain\Cup\CupType;
use App\Domain\Shared\Footprint;

final readonly class UpdateCup
{
    public function __construct(
        private string $id,
        private CupDto $dto,
        private TokenFootprint $footprint,
    ) {
    }

    public function footprint(): Footprint
    {
        return Footprint::fromArray($this->footprint->toArray());
    }

    public function id(): CupId
    {
        return CupId::fromString($this->id);
    }

    public function info(): CupInfo
    {
        return new CupInfo(
            $this->dto->name,
            $this->dto->eventCounts,
            $this->dto->year,
            CupType::from($this->dto->type),
        );
    }
}
