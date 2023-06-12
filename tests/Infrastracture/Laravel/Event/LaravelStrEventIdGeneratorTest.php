<?php

declare(strict_types=1);

namespace Tests\Infrastracture\Laravel\Event;

use App\Domain\Event\EventId;
use App\Infrastracture\Laravel\Event\LaravelStrEventIdGenerator;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as BaseUuid;

class LaravelStrEventIdGeneratorTest extends TestCase
{
    /** @test */
    public function it_generate_next_competition_id(): void
    {
        $generator = new LaravelStrEventIdGenerator();
        $competitionId = $generator->nextId();

        $this->assertInstanceOf(EventId::class, $competitionId);
        $this->assertTrue(BaseUuid::isValid($competitionId->toString()));
    }
}
