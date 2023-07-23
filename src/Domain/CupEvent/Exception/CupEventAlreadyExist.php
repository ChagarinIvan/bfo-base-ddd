<?php

declare(strict_types=1);

namespace App\Domain\CupEvent\Exception;

use App\Domain\CupEvent\CupEvent;
use DomainException;
use function sprintf;

final class CupEventAlreadyExist extends DomainException
{
    public static function byCupEvent(CupEvent $cupEvent): self
    {
        return new self(sprintf(
            'Cup event for cup "%s" on event "%s" already exist.',
            $cupEvent->cupId()->toString(),
            $cupEvent->eventId()->toString(),
        ));
    }
}
