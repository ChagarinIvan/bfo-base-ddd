<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Shared\Impression;

abstract class AggregatedRoot
{
    protected Impression $updated;

    protected Impression $created;

    protected bool $disabled = false;

    public function __construct(
        protected AggregatedRootId $id,
        Impression $impression,
    ) {
        $this->updated = $impression;
        $this->created = $impression;
    }

    public function disable(Impression $impression): void
    {
        $this->updated = $impression;
        $this->disabled = true;
    }

    public function id(): AggregatedRootId
    {
        return $this->id;
    }

    public function updated(): Impression
    {
        return $this->updated;
    }

    public function created(): Impression
    {
        return $this->created;
    }

    public function disabled(): bool
    {
        return $this->disabled;
    }
}
