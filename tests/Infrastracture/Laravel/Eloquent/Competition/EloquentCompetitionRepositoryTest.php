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

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function listCriteriaDataProvider(): array
    {
        return [
            'pagination' => [new Criteria(['page' => 1, 'perPage' => 2]), 2, '1b07ca91-1e16-4b5b-b459-341ca9e79aa9', 3],
            'filter_by_name' => [new Criteria(['page' => 1, 'perPage' => 2, 'name' => '1']), 1, '1b07ca91-1e16-4b5b-b459-341ca9e79aa9', 1],
            'filter_by_description' => [new Criteria(['page' => 1, 'perPage' => 2, 'description' => '1']), 1, '3a48ca7e-13bc-4198-80ba-237384dbf9a6', 1],
            'filter_by_from' => [new Criteria(['page' => 1, 'perPage' => 2, 'from' => '2023-02-01']), 2, '1b07ca91-1e16-4b5b-b459-341ca9e79aa9', 2],
            'filter_by_name_and_to' => [new Criteria(['page' => 1, 'perPage' => 2, 'name' => '1', 'to' => '2023-02-02']), 1, '1b07ca91-1e16-4b5b-b459-341ca9e79aa9', 1],
        ];
    }

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
        $this->assertEquals(new DateTimeImmutable('2023-02-01'), $competition->from());
        $this->assertEquals(new DateTimeImmutable('2023-02-02'), $competition->to());
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
        $this->assertEquals(new DateTimeImmutable('2023-02-01'), $competition->from());
        $this->assertEquals(new DateTimeImmutable('2023-02-02'), $competition->to());
        $this->assertEquals(new DateTimeImmutable('2022-01-01'), $competition->created()->at);
        $this->assertEquals(BaseUuid::NIL, $competition->created()->by->id->toString());
        $this->assertEquals(new DateTimeImmutable('2022-01-01'), $competition->updated()->at);
        $this->assertEquals(BaseUuid::NIL, $competition->updated()->by->id->toString());
    }

    /**
     * @test
     * @dataProvider listCriteriaDataProvider
     */
    public function it_gets_list_of_competition_by_criteria(Criteria $criteria, int $count, string $id, int $total): void
    {
        $this->seed(CompetitionFakeSeeder::class);
        $result = $this->competitions->byCriteria($criteria);
        $competitions = $result->items;

        $this->assertNotNull($competitions);
        $this->assertIsList($competitions);
        $this->assertContainsOnlyInstancesOf(Competition::class, $competitions);
        $this->assertCount($count, $competitions);
        $this->assertEquals($id, $competitions[0]->id()->toString());
        $this->assertEquals($total, $result->total);
    }
}
