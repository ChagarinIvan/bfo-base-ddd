<?php

declare(strict_types=1);

namespace App\Domain\Competition;

use App\Domain\AggregatedRoot;
use App\Domain\Shared\Impression;
use DateTimeImmutable;

final class Competition extends AggregatedRoot
{
    public function __construct(
        CompetitionId $id,
        private CompetitionInfo $info,
        Impression $impression,
    ) {
        parent::__construct($id, $impression);
    }

    public function updateInfo(CompetitionInfo $info, Impression $impression): void
    {
        $this->info = $info;
        $this->updated = $impression;
    }

    public function name(): string
    {
        return $this->info->name;
    }

    public function description(): string
    {
        return $this->info->description;
    }

    public function from(): DateTimeImmutable
    {
        return $this->info->from;
    }

    public function to(): DateTimeImmutable
    {
        return $this->info->to;
    }
}
