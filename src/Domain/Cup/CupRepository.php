<?php

declare(strict_types=1);

namespace App\Domain\Cup;

interface CupRepository
{
    public function byId(CupId $id): ?Cup;
}
