<?php

declare(strict_types=1);

namespace App\Domain\Cup;

use App\Domain\Cup\Group\CupGroup;

final readonly class CupTypeDefinition
{
    public function __construct(
        public CupType $type,
        /** @var CupGroup[] $groups */
        public array $groups,
    ) {
    }
}
