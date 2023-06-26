<?php

declare(strict_types=1);

namespace App\Domain\CupEvent\Calculator\Exception;

use App\Domain\Cup\Group\CupGroup;
use DomainException;
use function sprintf;

final class HasNoDistances extends DomainException
{
    public static function byGroup(CupGroup $group): self
    {
        return new self(sprintf('Cup event has no distances for group "%s".', $group->toString()));
    }
}
