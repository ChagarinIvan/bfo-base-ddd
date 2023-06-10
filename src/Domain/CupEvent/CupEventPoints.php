<?php

declare(strict_types=1);

namespace App\Domain\CupEvent;

use App\Domain\Person\PersonId;

final readonly class CupEventPoints
{
    public function __construct(
        public CupEventId $cupEventId,
        public PersonId $personId,
        public int $points,
    ) {
    }
}
