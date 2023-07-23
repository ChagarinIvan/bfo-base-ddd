<?php

declare(strict_types=1);

namespace Tests\Infrastracture\Laravel\CupEvent;

use App\Domain\CupEvent\CupEventId;
use App\Infrastracture\Laravel\CupEvent\LaravelStrCupEventIdGenerator;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as BaseUuid;

class LaravelStrCupEventIdGeneratorTest extends TestCase
{
    /** @test */
    public function it_generate_next_cup_event_id(): void
    {
        $generator = new LaravelStrCupEventIdGenerator();
        $cupId = $generator->nextId();

        $this->assertInstanceOf(CupEventId::class, $cupId);
        $this->assertTrue(BaseUuid::isValid($cupId->toString()));
    }
}
