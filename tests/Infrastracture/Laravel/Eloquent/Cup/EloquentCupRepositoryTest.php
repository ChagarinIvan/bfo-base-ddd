<?php

declare(strict_types=1);

namespace Tests\Infrastracture\Laravel\Eloquent\Cup;

use App\Domain\Cup\Cup;
use App\Domain\Cup\CupId;
use App\Domain\Cup\CupType;
use App\Domain\Shared\Criteria;
use App\Infrastracture\Laravel\Eloquent\Cup\EloquentCupRepository;
use Database\Seeders\Fakes\CupFakeSeeder;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Ramsey\Uuid\Uuid as BaseUuid;
use Tests\Faker\Cup\CupFaker;
use Tests\TestCase;

class EloquentCupRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    private EloquentCupRepository $cups;

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function listCriteriaDataProvider(): array
    {
        return [
            'pagination' => [new Criteria(['page' => 1, 'perPage' => 2]), 2, '1b07ca91-1e16-4b5b-b459-341ca9e79aa9', 3],
            'filter_by_name' => [new Criteria(['page' => 1, 'perPage' => 2, 'name' => '1']), 2, '1b07ca91-1e16-4b5b-b459-341ca9e79aa9', 2],
            'filter_by_type' => [new Criteria(['page' => 1, 'perPage' => 2, 'type' => 'bike']), 1, '1b07ca91-1e16-4b5b-b459-341ca9e79aa9', 1],
            'filter_by_year' => [new Criteria(['page' => 1, 'perPage' => 2, 'year' => '2021']), 1, '1b07ca91-1e16-4b5b-b459-341ca9e79aa9', 1],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->cups = new EloquentCupRepository();
    }

    /** @test */
    public function it_adds_cup_in_db(): void
    {
        $cup = CupFaker::fakeCup();
        $this->assertDatabaseEmpty('ddd_cup');
        $this->cups->add($cup);
        $this->assertDatabaseCount('ddd_cup', 1);
    }

    /** @test */
    public function it_gets_cup_by_id(): void
    {
        $this->seed(CupFakeSeeder::class);
        $cup = $this->cups->byId(CupId::fromString('1fc7e705-ef72-47b2-ba4e-55779b02c61f'));

        $this->assertNotNull($cup);
        $this->assertEquals('1fc7e705-ef72-47b2-ba4e-55779b02c61f', $cup->id()->toString());
        $this->assertEquals('cup name', $cup->name());
        $this->assertEquals(3, $cup->eventsCount());
        $this->assertEquals(2023, $cup->year());
        $this->assertEquals(CupType::ELITE, $cup->type());
        $this->assertEquals(new DateTimeImmutable('2022-01-01'), $cup->created()->at);
        $this->assertEquals(BaseUuid::NIL, $cup->created()->by->id->toString());
        $this->assertEquals(new DateTimeImmutable('2022-01-01'), $cup->updated()->at);
        $this->assertEquals(BaseUuid::NIL, $cup->updated()->by->id->toString());
    }

    /** @test */
    public function it_gets_cup_by_id_with_lock(): void
    {
        $this->seed(CupFakeSeeder::class);
        $cup = $this->cups->lockById(CupId::fromString('1fc7e705-ef72-47b2-ba4e-55779b02c61f'));

        $this->assertNotNull($cup);
        $this->assertEquals('1fc7e705-ef72-47b2-ba4e-55779b02c61f', $cup->id()->toString());
        $this->assertEquals('cup name', $cup->name());
        $this->assertEquals(3, $cup->eventsCount());
        $this->assertEquals(2023, $cup->year());
        $this->assertEquals(CupType::ELITE, $cup->type());
        $this->assertEquals(new DateTimeImmutable('2022-01-01'), $cup->created()->at);
        $this->assertEquals(BaseUuid::NIL, $cup->created()->by->id->toString());
        $this->assertEquals(new DateTimeImmutable('2022-01-01'), $cup->updated()->at);
        $this->assertEquals(BaseUuid::NIL, $cup->updated()->by->id->toString());
    }

    /** @test */
    public function it_updates_cup_in_db(): void
    {
        $this->seed(CupFakeSeeder::class);
        $cup = CupFaker::fakeCup(name: 'updated name');
        $this->cups->update($cup);

        $this->assertDatabaseHas('ddd_cup', [
            'name' => 'updated name',
            'id' => '1fc7e705-ef72-47b2-ba4e-55779b02c61f',
        ]);
    }

    /**
     * @test
     * @dataProvider listCriteriaDataProvider
     */
    public function it_gets_list_of_cup_by_criteria(Criteria $criteria, int $count, string $id, int $total): void
    {
        $this->seed(CupFakeSeeder::class);
        $result = $this->cups->byCriteria($criteria);
        $cups = $result->items;

        $this->assertNotNull($cups);
        $this->assertIsList($cups);
        $this->assertContainsOnlyInstancesOf(Cup::class, $cups);
        $this->assertCount($count, $cups);
        $this->assertEquals($id, $cups[0]->id()->toString());
        $this->assertEquals($total, $result->total);
    }
}
