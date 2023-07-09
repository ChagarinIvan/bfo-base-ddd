<?php

declare(strict_types=1);

namespace Tests\Infrastracture\Laravel\Cup;

use App\Domain\Cup\CupId;
use App\Infrastracture\Laravel\Cup\LaravelStrCupIdGenerator;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as BaseUuid;

class LaravelStCupIdGeneratorTest extends TestCase
{
    /** @test */
    public function it_generate_next_cup_id(): void
    {
        $generator = new LaravelStrCupIdGenerator();
        $cupId = $generator->nextId();

        $this->assertInstanceOf(CupId::class, $cupId);
        $this->assertTrue(BaseUuid::isValid($cupId->toString()));
    }
}
