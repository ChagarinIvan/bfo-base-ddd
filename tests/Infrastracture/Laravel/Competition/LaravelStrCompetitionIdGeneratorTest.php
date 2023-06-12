<?php

declare(strict_types=1);

namespace Tests\Infrastracture\Laravel\Competition;

use App\Domain\Competition\CompetitionId;
use App\Infrastracture\Laravel\Competition\LaravelStrCompetitionIdGenerator;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as BaseUuid;

class LaravelStrCompetitionIdGeneratorTest extends TestCase
{
    /** @test */
    public function it_generate_next_competition_id(): void
    {
        $generator = new LaravelStrCompetitionIdGenerator();
        $competitionId = $generator->nextId();

        $this->assertInstanceOf(CompetitionId::class, $competitionId);
        $this->assertTrue(BaseUuid::isValid($competitionId->toString()));
    }
}
