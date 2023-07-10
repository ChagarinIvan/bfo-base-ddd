<?php

declare(strict_types=1);

namespace App\Application\Service\Cup;

use App\Domain\Cup\CupId;

final readonly class ViewCup
{
    public function __construct(private string $id)
    {
    }

    public function id(): CupId
    {
        return CupId::fromString($this->id);
    }
}
