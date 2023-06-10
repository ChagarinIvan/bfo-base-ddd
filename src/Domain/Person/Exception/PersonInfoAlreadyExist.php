<?php

declare(strict_types=1);

namespace App\Domain\Person\Exception;

use App\Domain\Person\PersonInfo;
use DomainException;
use function sprintf;
use function ucfirst;

final class PersonInfoAlreadyExist extends DomainException
{
    public static function byInfo(PersonInfo $info): self
    {
        return new self(sprintf(
            'Person "%s %s - %s" already exist.',
            ucfirst($info->firstname),
            ucfirst($info->lastname),
            $info->yearOfBirthday ?: 'unknown year',
        ));
    }
}
