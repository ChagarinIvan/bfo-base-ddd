<?php

declare(strict_types=1);

namespace App\Domain\CupEvent;

interface CupEventRepository
{
    public function byId(CupEventId $id): ?CupEvent;
}