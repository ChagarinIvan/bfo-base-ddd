<?php

declare(strict_types=1);

namespace Tests\Faker\Cup;

use App\Domain\Cup\Cup;
use App\Domain\Cup\CupId;
use App\Domain\Cup\CupType;
use Tests\Faker\Shared\AuthFaker;

class CupFaker
{
    public static function fakeCup(
        string $id = '1fc7e705-ef72-47b2-ba4e-55779b02c61f',
        string $name = 'cup name',
        CupType $type = CupType::ELITE,
    ): Cup {
        return new Cup(
            id: CupId::fromString($id),
            name: $name,
            eventsCount: 3,
            year: 2023,
            type: $type,
            impression: AuthFaker::fakeImpression()
        );
    }
}
