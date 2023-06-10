<?php

declare(strict_types=1);

namespace Tests\Infrastracture\Laravel\Eloquent\Competition;

use App\Domain\Competition\CompetitionId;
use App\Infrastracture\Laravel\Eloquent\Competition\EloquentCompetitionRepository;
use Database\Seeders\Fakes\CompetitionFakeSeeder;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Ramsey\Uuid\Uuid as BaseUuid;
use Tests\Faker\Competition\CompetitionFaker;
use Tests\TestCase;

class EloquentCompetitionRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private EloquentCompetitionRepository $competitions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->competitions = new EloquentCompetitionRepository();
    }

    /** @test */
    public function it_adds_competition_in_db(): void
    {
        $competition = CompetitionFaker::fakeCompetition();
        $this->assertDatabaseEmpty('ddd_competition');
        $this->competitions->add($competition);
        $this->assertDatabaseCount('ddd_competition', 1);
    }

    /** @test */
    public function it_updates_competition_in_db(): void
    {
        $this->seed(CompetitionFakeSeeder::class);
        $competition = CompetitionFaker::fakeCompetition(name: 'updated name');
        $this->competitions->update($competition);

        $this->assertDatabaseHas('ddd_competition', [
            'name' => 'updated name',
            'id' => '1fc7e705-ef72-47b2-ba4e-55779b02c61f',
        ]);
    }

    /** @test */
    public function it_get_competition_with_lock(): void
    {
        $this->seed(CompetitionFakeSeeder::class);
        $competition = $this->competitions->lockById(CompetitionId::fromString('1fc7e705-ef72-47b2-ba4e-55779b02c61f'));

        $this->assertNotNull($competition);
        $this->assertEquals('1fc7e705-ef72-47b2-ba4e-55779b02c61f', $competition->id()->toString());
        $this->assertEquals('test competition', $competition->name());
        $this->assertEquals('test competition description', $competition->description());
        $this->assertEquals(new DateTimeImmutable('2023-01-01'), $competition->from());
        $this->assertEquals(new DateTimeImmutable('2023-01-02'), $competition->to());
        $this->assertEquals(new DateTimeImmutable('2022-01-01'), $competition->created()->at);
        $this->assertEquals(BaseUuid::NIL, $competition->created()->by->id->toString());
        $this->assertEquals(new DateTimeImmutable('2022-01-01'), $competition->updated()->at);
        $this->assertEquals(BaseUuid::NIL, $competition->updated()->by->id->toString());
    }
}
