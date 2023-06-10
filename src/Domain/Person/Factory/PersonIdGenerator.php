<?php

declare(strict_types=1);

namespace App\Domain\Person\Factory;

use App\Domain\Person\PersonId;

interface PersonIdGenerator
{
    public function nextId(): PersonId;
}
