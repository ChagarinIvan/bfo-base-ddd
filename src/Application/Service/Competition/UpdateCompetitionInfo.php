<?php

declare(strict_types=1);

namespace App\Application\Service\Competition;

use App\Application\Dto\Competition\CompetitionInfoDto;
use App\Application\Dto\Shared\TokenFootprint;
use App\Domain\Competition\CompetitionId;
use App\Domain\Competition\CompetitionInfo;
use App\Domain\Shared\Footprint;
use DateTimeImmutable;

final readonly class UpdateCompetitionInfo
{
    public function __construct(
        private string $id,
        private CompetitionInfoDto $dto,
        private TokenFootprint $footprint
    ) {
    }

    public function info(): CompetitionInfo
    {
        return new CompetitionInfo(
            $this->dto->name,
            $this->dto->description,
            new DateTimeImmutable($this->dto->from),
            new DateTimeImmutable($this->dto->to),
        );
    }

    public function footprint(): Footprint
    {
        return Footprint::fromArray($this->footprint->toArray());
    }

    public function id(): CompetitionId
    {
        return CompetitionId::fromString($this->id);
    }
}
