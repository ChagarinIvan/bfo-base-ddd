<?php

declare(strict_types=1);

namespace App\Domain\Cup;

use App\Domain\AggregatedRoot;
use App\Domain\Shared\Impression;

final class Cup extends AggregatedRoot
{
    private bool $disabled = false;

    public function __construct(
        CupId $id,
        private readonly string $name,
        private readonly int $eventsCount,
        private readonly int $year,
        private readonly CupType $type,
        Impression $impression,
    ) {
        parent::__construct($id, $impression);
    }

    public function disable(Impression $impression): void
    {
        $this->updated = $impression;
        $this->disabled = true;
    }

    public function disabled(): bool
    {
        return $this->disabled;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function eventsCount(): int
    {
        return $this->eventsCount;
    }

    public function year(): int
    {
        return $this->year;
    }

    public function type(): CupType
    {
        return $this->type;
    }
}
