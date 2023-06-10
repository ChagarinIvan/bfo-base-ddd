<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Shared\Impression;

abstract class AggregatedRoot
{
    protected Impression $updated;

    protected Impression $created;

    public function __construct(
        private readonly AggregatedRootId $id,
        Impression $impression,
    ) {
        $this->updated = $impression;
        $this->created = $impression;
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
}
