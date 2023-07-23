<?php

declare(strict_types=1);

namespace App\Domain\Club;

use App\Domain\AggregatedRoot;
use App\Domain\Club\Factory\ClubInput;
use App\Domain\Shared\Impression;

final class Club extends AggregatedRoot
{
    public function __construct(
        ClubId $id,
        private string $name,
        Impression $impression,
    ) {
        parent::__construct($id, $impression);
    }

    public function update(ClubInput $input, Impression $impression): void
    {
        $this->name = $input->name;
        $this->updated = $impression;
    }

    public function name(): string
    {
        return $this->name;
    }
}
