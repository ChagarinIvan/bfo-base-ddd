<?php

declare(strict_types=1);

namespace Tests\Infrastracture\Laravel\Eloquent\Club;

use App\Domain\Club\Club;
use App\Domain\Club\ClubId;
use App\Domain\Shared\Criteria;
use App\Infrastracture\Laravel\Eloquent\Club\EloquentClubRepository;
use Database\Seeders\Fakes\ClubFakeSeeder;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Ramsey\Uuid\Uuid as BaseUuid;
use Tests\Faker\Club\ClubFaker;
use Tests\TestCase;

class EloquentClubRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    private EloquentClubRepository $clubs;

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function listCriteriaDataProvider(): array
    {
        return [
            'pagination' => [new Criteria(['page' => 1, 'perPage' => 2]), 2, 'a25c7b35-dae6-4d92-8f62-d3dcdfa22f51', 3],
            'filter_by_name' => [new Criteria(['page' => 1, 'perPage' => 2, 'name_like' => '3']), 1, 'ab79834f-494b-4137-9280-eb496328addf', 1],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->clubs = new EloquentClubRepository();
    }

    /** @test */
    public function it_adds_club_in_db(): void
    {
        $club = ClubFaker::fakeClub();
        $this->assertDatabaseEmpty('ddd_club');
        $this->clubs->add($club);
        $this->assertDatabaseCount('ddd_club', 1);
    }

    /** @test */
    public function it_updates_club_in_db(): void
    {
        $this->seed(ClubFakeSeeder::class);
        $club = ClubFaker::fakeClub(name: 'updated name');
        $this->clubs->update($club);

        $this->assertDatabaseHas('ddd_club', [
            'name' => 'updated name',
            'id' => '1fc7e705-ef72-47b2-ba4e-55779b02c61f',
        ]);
    }

    /** @test */
    public function it_get_club_with_lock(): void
    {
        $this->seed(ClubFakeSeeder::class);
        $club = $this->clubs->lockById(ClubId::fromString('1fc7e705-ef72-47b2-ba4e-55779b02c61f'));

        $this->assertNotNull($club);
        $this->assertEquals('1fc7e705-ef72-47b2-ba4e-55779b02c61f', $club->id()->toString());
        $this->assertEquals('tеst сlub', $club->name());
        $this->assertEquals(new DateTimeImmutable('2022-01-01'), $club->created()->at);
        $this->assertEquals(BaseUuid::NIL, $club->created()->by->id->toString());
        $this->assertEquals(new DateTimeImmutable('2022-01-01'), $club->updated()->at);
        $this->assertEquals(BaseUuid::NIL, $club->updated()->by->id->toString());
    }

    /** @test */
    public function it_gets_club_by_id(): void
    {
        $this->seed(ClubFakeSeeder::class);
        $club = $this->clubs->byId(ClubId::fromString('1fc7e705-ef72-47b2-ba4e-55779b02c61f'));

        $this->assertNotNull($club);
        $this->assertEquals('1fc7e705-ef72-47b2-ba4e-55779b02c61f', $club->id()->toString());
        $this->assertEquals('tеst сlub', $club->name());
        $this->assertEquals(new DateTimeImmutable('2022-01-01'), $club->created()->at);
        $this->assertEquals(BaseUuid::NIL, $club->created()->by->id->toString());
        $this->assertEquals(new DateTimeImmutable('2022-01-01'), $club->updated()->at);
        $this->assertEquals(BaseUuid::NIL, $club->updated()->by->id->toString());
    }

    /** @test */
    public function it_get_one_by_criteria(): void
    {
        $this->seed(ClubFakeSeeder::class);
        $club = $this->clubs->oneByCriteria(new Criteria(['name' => 'test club 3']));

        $this->assertNotNull($club);
        $this->assertEquals('ab79834f-494b-4137-9280-eb496328addf', $club->id()->toString());
        $this->assertEquals('test club 3', $club->name());
    }

    /**
     * @test
     * @dataProvider listCriteriaDataProvider
     */
    public function it_gets_list_of_clubs_by_criteria(Criteria $criteria, int $count, string $id, int $total): void
    {
        $this->seed(ClubFakeSeeder::class);
        $result = $this->clubs->byCriteria($criteria);
        $clubs = $result->items;

        $this->assertNotNull($clubs);
        $this->assertIsList($clubs);
        $this->assertContainsOnlyInstancesOf(Club::class, $clubs);
        $this->assertCount($count, $clubs);
        $this->assertEquals($id, $clubs[0]->id()->toString());
        $this->assertEquals($total, $result->total);
    }
}
