<?php

declare(strict_types=1);

namespace App\Domain\CupEvent\Calculator\Exception;

use App\Domain\Cup\CupId;
use DomainException;
use function sprintf;

final class CupNotExist extends DomainException
{
    public static function byId(CupId $id): self
    {
        return new self(sprintf('Cup "%s" not found.', $id->toString()));
    }
}
