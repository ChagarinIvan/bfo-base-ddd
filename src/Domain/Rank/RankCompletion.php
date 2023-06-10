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
        private int $id,
        private PersonId $personId,
        private EventId $eventId,
        private RankType $type,
        private DateTimeImmutable $completedAt,
        private Impression $createdAt,
    ) {
    }
}
