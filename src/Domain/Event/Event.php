<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\AggregatedRoot;
use App\Domain\Competition\CompetitionId;
use App\Domain\Shared\Impression;
use DateTimeImmutable;

final class Event extends AggregatedRoot
{
    public function __construct(
        EventId $id,
        private readonly CompetitionId $competitionId,
        private EventInfo $info,
        Impression $impression,
    ) {
        parent::__construct($id, $impression);
    }

    public function name(): string
    {
        return $this->info->name;
    }

    public function description(): string
    {
        return $this->info->description;
    }

    public function date(): DateTimeImmutable
    {
        return $this->info->date;
    }

    public function competitionId(): CompetitionId
    {
        return $this->competitionId;
    }

    public function updateInfo(EventInfo $info, Impression $impression): void
    {
        $this->info = $info;
        $this->updated = $impression;
    }
}
