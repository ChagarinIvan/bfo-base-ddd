<?php

declare(strict_types=1);

namespace App\Domain\Rank;

use App\Domain\Event\EventId;
use App\Domain\Person\PersonId;
use App\Domain\Shared\Impression;
use DateTimeImmutable;

final readonly class RankCompletion
{
    public function __construct(
        public int $id,
        public PersonId $personId,
        public EventId $eventId,
        public RankType $type,
        public DateTimeImmutable $completedAt,
        public Impression $createdAt,
    ) {
    }
}
