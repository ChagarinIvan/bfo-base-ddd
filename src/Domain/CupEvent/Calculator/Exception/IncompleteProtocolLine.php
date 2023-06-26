<?php

declare(strict_types=1);

namespace App\Domain\CupEvent\Calculator\Exception;

use App\Domain\ProtocolLine\ProtocolLineId;
use DomainException;
use function sprintf;

final class IncompleteProtocolLine extends DomainException
{
    public static function byId(ProtocolLineId $id): self
    {
        return new self(sprintf('Protocol line "%s" in incomplete state.', $id->toString()));
    }
}
