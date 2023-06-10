<?php

declare(strict_types=1);

namespace App\Domain\Cup;

enum CupType: string
{
    public function toString(): string
    {
        return $this->value;
    }
    case ELITE = 'elite';
    case MASTER = 'master';
    case SPRINT = 'sprint';
    case BIKE = 'bike';
    case JUNIORS = 'juniors';
    case YOUTH = 'youth';
    case SKI = 'ski';
}
