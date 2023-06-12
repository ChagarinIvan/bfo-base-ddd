<?php

declare(strict_types=1);

namespace Tests\Infrastracture\Laravel\Eloquent\Competition;

use App\Domain\Competition\Competition;
use App\Domain\Competition\CompetitionId;
use App\Domain\Shared\Criteria;
use App\Infrastracture\Laravel\Eloquent\Competition\EloquentCompetitionRepository;
use Database\Seeders\Fakes\CompetitionFakeSeeder;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Ramsey\Uuid\Uuid as BaseUuid;
use Tests\Faker\Competition\CompetitionFaker;
use Tests\TestCase;

class EloquentCompetitionRepositoryTest extends TestCase
{
    use DatabaseTransactions;

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
    public function it_gets_competition_by_id(): void
    {
        $this->seed(CompetitionFakeSeeder::class);
        $competition = $this->competitions->byId(CompetitionId::fromString('1fc7e705-ef72-47b2-ba4e-55779b02c61f'));

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

    /** @test */
    public function it_gets_competition_by_id_with_lock(): void
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

    /** @test */
    public function it_gets_list_of_competition_by_criteria(): void
    {
        $this->seed(CompetitionFakeSeeder::class);
        $competitions = $this->competitions->byCriteria(new Criteria(['page' => 1, 'perPage' => 1]));

        $this->assertNotNull($competitions);
        $this->assertIsList($competitions);
        $this->assertContainsOnlyInstancesOf(Competition::class, $competitions);
        $this->assertCount(1, $competitions);
        $this->assertEquals('3a48ca7e-13bc-4198-80ba-237384dbf9a6', $competitions[0]->id()->toString());
    }
}
