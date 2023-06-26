<?php

declare(strict_types=1);

namespace App\Domain\ProtocolLine;

final readonly class ProtocolLineRowData
{
    public function __construct(
        public string $serialNumber,
        public string $firstname,
        public string $lastname,
        public string $club,
        public string $year,
        public string $rank,
        public string $runnerNumber,
        public string $time,
        public string $place,
        public string $completedRank,
        public string $points,
        public string $outOfCompetition,
    ) {
    }
}
