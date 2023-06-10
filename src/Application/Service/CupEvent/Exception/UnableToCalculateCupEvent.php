<?php

declare(strict_types=1);

namespace App\Application\Service\CupEvent\Exception;

use DomainException;
use RuntimeException;
use function sprintf;

final class UnableToCalculateCupEvent extends RuntimeException
{
    public static function dueError(DomainException $e): self
    {
        return new self(sprintf('Unable to calculate cup event. Reason: %s', $e->getMessage()), previous: $e);
    }
}
