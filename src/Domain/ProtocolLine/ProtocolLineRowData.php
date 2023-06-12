<?php

declare(strict_types=1);

namespace App\Domain\ProtocolLine;

final class ProtocolLineRowData
{
    public function __construct(
        public readonly string $serialNumber,
        public readonly string $firstname,
        public readonly string $lastname,
        public readonly string $club,
        public readonly string $year,
        public readonly string $rank,
        public readonly string $runnerNumber,
        public readonly string $time,
        public readonly string $place,
        public readonly string $completedRank,
        public readonly string $points,
        public readonly string $outOfCompetition,
    ) {
    }
}
