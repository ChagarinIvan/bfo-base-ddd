<?php

declare(strict_types=1);

namespace App\Application\Service\CupEvent;

use App\Domain\CupEvent\CupEventId;

final readonly class ViewCupEvent
{
    public function __construct(private string $id)
    {
    }

    public function id(): CupEventId
    {
        return CupEventId::fromString($this->id);
    }
}
