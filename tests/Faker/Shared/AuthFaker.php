<?php

declare(strict_types=1);

namespace Tests\Faker\Shared;

use App\Application\Dto\Shared\TokenFootprint;
use App\Domain\Shared\Footprint;
use App\Domain\Shared\Impression;
use App\Domain\User\UserId;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid as BaseUuid;

class AuthFaker
{
    public static function fakeImpression(): Impression
    {
        return new Impression(
            new DateTimeImmutable('2022-01-01'),
            self::fakeFootprint(),
        );
    }

    public static function fakeFootprint(): Footprint
    {
        return new Footprint(UserId::fromString(BaseUuid::NIL));
    }

    public static function fakeFootprintDto(): TokenFootprint
    {
        $token = new TokenFootprint();
        $token->id = BaseUuid::NIL;

        return $token;
    }
}
