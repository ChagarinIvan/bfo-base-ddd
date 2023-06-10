<?php

declare(strict_types=1);

namespace App\Application\Service\Competition;

use App\Application\Dto\Competition\CompetitionInfoDto;
use App\Application\Dto\Shared\TokenFootprint;
use App\Domain\Competition\CompetitionInfo;
use App\Domain\Competition\Factory\CompetitionInput;
use App\Domain\Shared\Footprint;
use DateTimeImmutable;

final readonly class AddCompetition
{
    public function __construct(
        private CompetitionInfoDto $dto,
        private TokenFootprint $footprint,
    ) {
    }

    public function competitionInput(): CompetitionInput
    {
        return new CompetitionInput(
            $this->info(),
            $this->footprint(),
        );
    }

    private function info(): CompetitionInfo
    {
        return new CompetitionInfo(
            $this->dto->name,
            $this->dto->description,
            new DateTimeImmutable($this->dto->from),
            new DateTimeImmutable($this->dto->to),
        );
    }

    private function footprint(): Footprint
    {
        return Footprint::fromArray($this->footprint->toArray());
    }
}
