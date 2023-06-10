<?php

declare(strict_types=1);

namespace App\Domain\Rank;

use App\Domain\AggregatedRoot;
use App\Domain\Person\PersonId;
use App\Domain\Shared\Collection;
use App\Domain\Shared\Impression;
use DateTimeImmutable;

final class Rank extends AggregatedRoot
{
    public function __construct(
        RankId $id,
        private PersonId $personId,
        private RankType $type,
        private DateTimeImmutable $completedAt,
        private DateTimeImmutable $startedAt,
        private DateTimeImmutable $finishedAt,
        private Collection $completions,
        Impression $impression,
    ) {
        parent::__construct($id, $impression);
    }

    public function personId(): PersonId
    {
        return $this->personId;
    }

    public function type(): RankType
    {
        return $this->type;
    }

    public function completedAt(): DateTimeImmutable
    {
        return $this->completedAt;
    }

    public function startedAt(): DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function finishedAt(): DateTimeImmutable
    {
        return $this->finishedAt;
    }

    public function completions(): Collection
    {
        return $this->completions;
    }
}
