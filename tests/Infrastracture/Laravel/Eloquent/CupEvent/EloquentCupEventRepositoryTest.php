<?php

declare(strict_types=1);

namespace Tests\Infrastracture\Laravel\Eloquent\CupEvent;

use App\Domain\CupEvent\CupEvent;
use App\Domain\CupEvent\CupEventId;
use App\Domain\Distance\DistanceId;
use App\Domain\Shared\Criteria;
use App\Infrastracture\Laravel\Eloquent\CupEvent\EloquentCupEventRepository;
use Database\Seeders\Fakes\CupEventFakeSeeder;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Ramsey\Uuid\Uuid as BaseUuid;
use Tests\Faker\CupEvent\CupEventFaker;
use Tests\TestCase;

class EloquentCupEventRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    private EloquentCupEventRepository $cupsEvents;

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function listCriteriaDataProvider(): array
    {
        return [
            'pagination' => [new Criteria(['page' => 1, 'perPage' => 2]), 2, '1b07ca91-1e16-4b5b-b459-341ca9e79aa9', 3],
            'filter_by_cup_id' => [new Criteria(['page' => 1, 'perPage' => 2, 'cupId' => 'b5f58bfd-1335-4e0c-8233-7dc2ab82181f']), 1, '3a48ca7e-13bc-4198-80ba-237384dbf9a6', 1],
            'filter_by_event_id' => [new Criteria(['page' => 1, 'perPage' => 2, 'eventId' => 'bb3bf8fc-929b-4769-9dad-9fc147a5b87f']), 1, '1b07ca91-1e16-4b5b-b459-341ca9e79aa9', 1],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->cupsEvents = new EloquentCupEventRepository();
    }

    /** @test */
    public function it_adds_cup_in_db(): void
    {
        $cupEvent = CupEventFaker::fakeCupEvent();
        $this->assertDatabaseEmpty('ddd_cup_event');
        $this->cupsEvents->add($cupEvent);
        $this->assertDatabaseCount('ddd_cup_event', 1);
    }

    /** @test */
    public function it_gets_cup_event_by_id(): void
    {
        $this->seed(CupEventFakeSeeder::class);
        $cupEvent = $this->cupsEvents->byId(CupEventId::fromString('1fc7e705-ef72-47b2-ba4e-55779b02c61f'));

        $this->assertNotNull($cupEvent);
        $this->assertEquals('1fc7e705-ef72-47b2-ba4e-55779b02c61f', $cupEvent->id()->toString());
        $this->assertEquals('1efaf3e4-a661-4a68-b014-669e03d1f895', $cupEvent->cupId()->toString());
        $this->assertEquals('56e6fb03-5869-427e-9bd3-14d8695500cf', $cupEvent->eventId()->toString());
        $this->assertEquals(1100, $cupEvent->points());
        $groupDistances = $cupEvent->groupsDistances()->all();
        $this->assertEquals('M_21', $groupDistances[0]->groupId);
        $this->assertEquals(DistanceId::fromString('b5f58bfd-1335-4e0c-8233-7dc2ab82181f'), $groupDistances[0]->distances[0]);
        $this->assertEquals(DistanceId::fromString('bb3bf8fc-929b-4769-9dad-9fc147a5b87f'), $groupDistances[0]->distances[1]);
        $this->assertEquals(new DateTimeImmutable('2022-01-01'), $cupEvent->created()->at);
        $this->assertEquals(BaseUuid::NIL, $cupEvent->created()->by->id->toString());
        $this->assertEquals(new DateTimeImmutable('2022-01-01'), $cupEvent->updated()->at);
        $this->assertEquals(BaseUuid::NIL, $cupEvent->updated()->by->id->toString());
    }

    /** @test */
    public function it_gets_cup_event_by_id_with_lock(): void
    {
        $this->seed(CupEventFakeSeeder::class);
        $cupEvent = $this->cupsEvents->lockById(CupEventId::fromString('3a48ca7e-13bc-4198-80ba-237384dbf9a6'));

        $this->assertNotNull($cupEvent);
        $this->assertEquals('3a48ca7e-13bc-4198-80ba-237384dbf9a6', $cupEvent->id()->toString());
        $this->assertEquals('b5f58bfd-1335-4e0c-8233-7dc2ab82181f', $cupEvent->cupId()->toString());
        $this->assertEquals('56e6fb03-5869-427e-9bd3-14d8695500cf', $cupEvent->eventId()->toString());
        $this->assertEquals(1100, $cupEvent->points());
        $groupDistances = $cupEvent->groupsDistances()->all();
        $this->assertEquals('M_21', $groupDistances[0]->groupId);
        $this->assertEquals(DistanceId::fromString('b5f58bfd-1335-4e0c-8233-7dc2ab82181f'), $groupDistances[0]->distances[0]);
        $this->assertEquals(DistanceId::fromString('bb3bf8fc-929b-4769-9dad-9fc147a5b87f'), $groupDistances[0]->distances[1]);
        $this->assertEquals(new DateTimeImmutable('2022-01-01'), $cupEvent->created()->at);
        $this->assertEquals(BaseUuid::NIL, $cupEvent->created()->by->id->toString());
        $this->assertEquals(new DateTimeImmutable('2022-01-01'), $cupEvent->updated()->at);
        $this->assertEquals(BaseUuid::NIL, $cupEvent->updated()->by->id->toString());
    }

    /** @test */
    public function it_updates_cup_event_in_db(): void
    {
        $this->seed(CupEventFakeSeeder::class);
        $cupEvent = CupEventFaker::fakeCupEvent(eventId: 'b5f58bfd-1335-4e0c-8233-7dc2ab82181f');
        $this->cupsEvents->update($cupEvent);

        $this->assertDatabaseHas('ddd_cup_event', [
            'id' => '1fc7e705-ef72-47b2-ba4e-55779b02c61f',
            'eventId' => 'b5f58bfd-1335-4e0c-8233-7dc2ab82181f',
        ]);
    }

    /** @test */
    public function it_gets_one_cup_event_by_criteria(): void
    {
        $this->seed(CupEventFakeSeeder::class);
        $cupEvent = $this->cupsEvents->oneByCriteria(new Criteria([
            'cupId' => '1efaf3e4-a661-4a68-b014-669e03d1f895',
            'eventId' => 'bb3bf8fc-929b-4769-9dad-9fc147a5b87f',
        ]));

        $this->assertInstanceOf(CupEvent::class, $cupEvent);
        $this->assertEquals('1b07ca91-1e16-4b5b-b459-341ca9e79aa9', $cupEvent->id()->toString());
    }

    /**
     * @test
     * @dataProvider listCriteriaDataProvider
     */
    public function it_gets_list_of_cup_events_by_criteria(Criteria $criteria, int $count, string $id, int $total): void
    {
        $this->seed(CupEventFakeSeeder::class);
        $result = $this->cupsEvents->byCriteria($criteria);
        $cups = $result->items;

        $this->assertNotNull($cups);
        $this->assertIsList($cups);
        $this->assertContainsOnlyInstancesOf(CupEvent::class, $cups);
        $this->assertCount($count, $cups);
        $this->assertEquals($id, $cups[0]->id()->toString());
        $this->assertEquals($total, $result->total);
    }
}
