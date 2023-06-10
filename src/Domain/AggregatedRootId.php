<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Shared\Uuid;

abstract readonly class AggregatedRootId extends Uuid
{
}
