<?php

declare(strict_types=1);

namespace Tests\Infrastracture\Laravel\Club;

use App\Domain\Club\ClubId;
use App\Infrastracture\Laravel\Club\LaravelStrClubIdGenerator;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as BaseUuid;

class LaravelStrClubIdGeneratorTest extends TestCase
{
    /** @test */
    public function it_generate_next_club_id(): void
    {
        $generator = new LaravelStrClubIdGenerator();
        $clubId = $generator->nextId();

        $this->assertInstanceOf(ClubId::class, $clubId);
        $this->assertTrue(BaseUuid::isValid($clubId->toString()));
    }
}
