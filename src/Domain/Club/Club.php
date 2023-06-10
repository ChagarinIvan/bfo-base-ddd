<?php

declare(strict_types=1);

namespace App\Domain\Club;

use App\Domain\AggregatedRoot;
use App\Domain\Shared\Impression;

final class Club extends AggregatedRoot
{
    private bool $disabled = false;

    public function __construct(
        ClubId $id,
        private string $name,
        Impression $impression,
    ) {
        parent::__construct($id, $impression);
    }

    public function update(UpdateInput $input, Impression $impression): void
    {
        $this->name = $input->name;
        $this->updated = $impression;
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
}
