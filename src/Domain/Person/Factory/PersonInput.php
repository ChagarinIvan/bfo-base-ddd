<?php

declare(strict_types=1);

namespace App\Domain\Person\Factory;

use App\Domain\Club\ClubId;
use App\Domain\Person\PersonInfo;
use App\Domain\Shared\Footprint;
use App\Domain\Shared\Metadata;

final readonly class PersonInput
{
    public function __construct(
        public PersonInfo $info,
        public Metadata $attributes,
        public Footprint $footprint,
        public ?ClubId $clubId = null,
    ) {
    }

    public function withInfo(PersonInfo $info): self
    {
        return new self($info, $this->attributes, $this->footprint, $this->clubId);
    }
}
