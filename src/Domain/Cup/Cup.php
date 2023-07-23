<?php

declare(strict_types=1);

namespace App\Domain\Cup;

use App\Domain\AggregatedRoot;
use App\Domain\Shared\Impression;

final class Cup extends AggregatedRoot
{
    public function __construct(
        CupId $id,
        private CupInfo $info,
        Impression $impression,
    ) {
        parent::__construct($id, $impression);
    }

    public function updateInfo(CupInfo $info, Impression $impression): void
    {
        $this->info = $info;
        $this->updated = $impression;
    }

    public function name(): string
    {
        return $this->info->name;
    }

    public function eventsCount(): int
    {
        return $this->info->eventsCount;
    }

    public function year(): int
    {
        return $this->info->year;
    }

    public function type(): CupType
    {
        return $this->info->type;
    }
}
