<?php

declare(strict_types=1);

namespace Tests\Infrastracture\Laravel\Eloquent\Club;

use App\Domain\Club\ClubId;
use App\Domain\Shared\Criteria;
use App\Infrastracture\Laravel\Eloquent\Club\EloquentClubRepository;
use Database\Seeders\Fakes\ClubFakeSeeder;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Ramsey\Uuid\Uuid as BaseUuid;
use Tests\Faker\Club\ClubFaker;
use Tests\TestCase;

class EloquentClubRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private EloquentClubRepository $clubs;

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
        $this->assertEquals('test club', $club->name());
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
}
