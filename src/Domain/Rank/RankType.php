<?php

declare(strict_types=1);

namespace App\Domain\Rank;

enum RankType: string
{
    case WSM = 'world_master';
    case SM = 'master';
    case SMC = 'master_candidate';
    case FIRST = 'first';
    case SECOND = 'second';
    case THIRD = 'third';
    case FIRST_JUNIOR = 'first_junior';
    case SECOND_JUNIOR = 'second_junior';
    case THIRD_JUNIOR = 'third_junior';
}
