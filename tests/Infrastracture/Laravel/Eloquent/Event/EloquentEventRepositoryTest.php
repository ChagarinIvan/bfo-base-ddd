<?php

declare(strict_types=1);

namespace Tests\Infrastracture\Laravel\Eloquent\Event;

use App\Domain\Event\Event;
use App\Domain\Event\EventId;
use App\Domain\Shared\Criteria;
use App\Infrastracture\Laravel\Eloquent\Event\EloquentEventRepository;
use Database\Seeders\Fakes\EventFakeSeeder;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Ramsey\Uuid\Uuid as BaseUuid;
use Tests\Faker\Event\EventFaker;
use Tests\TestCase;

class EloquentEventRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    private EloquentEventRepository $events;

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function listCriteriaDataProvider(): array
    {
        return [
            'pagination' => [new Criteria(['page' => 1, 'perPage' => 1]), 1, '1efaf3e4-a661-4a68-b014-669e03d1f895', 2],
            'filter_by_competition_id' => [new Criteria(['page' => 1, 'perPage' => 2, 'competitionId' => 'ff4d49a6-e5f0-49ea-8c5c-0bba1200aa97']), 1, '1efaf3e4-a661-4a68-b014-669e03d1f895', 1],
            'filter_by_name' => [new Criteria(['page' => 1, 'perPage' => 2, 'name' => '1']), 1, '1efaf3e4-a661-4a68-b014-669e03d1f895', 1],
            'filter_by_description' => [new Criteria(['page' => 1, 'perPage' => 2, 'description' => '1']), 1, '1efaf3e4-a661-4a68-b014-669e03d1f895', 1],
            'filter_by_date' => [new Criteria(['page' => 1, 'perPage' => 2, 'date' => '2023-02-01']), 1, '1efaf3e4-a661-4a68-b014-669e03d1f895', 1],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->events = new EloquentEventRepository();
    }

    /** @test */
    public function it_adds_event_in_db(): void
    {
        $event = EventFaker::fakeEvent();
        $this->assertDatabaseEmpty('ddd_event');
        $this->events->add($event);
        $this->assertDatabaseCount('ddd_event', 1);
    }

    /** @test */
    public function it_updates_event_in_db(): void
    {
        $this->seed(EventFakeSeeder::class);
        $event = EventFaker::fakeEvent(name: 'updated name');
        $this->events->update($event);

        $this->assertDatabaseHas('ddd_event', [
            'name' => 'updated name',
            'id' => '1fc7e705-ef72-47b2-ba4e-55779b02c61f',
        ]);
    }

    /** @test */
    public function it_get_event_with_lock(): void
    {
        $this->seed(EventFakeSeeder::class);
        $event = $this->events->lockById(EventId::fromString('1fc7e705-ef72-47b2-ba4e-55779b02c61f'));

        $this->assertNotNull($event);
        $this->assertEquals('1fc7e705-ef72-47b2-ba4e-55779b02c61f', $event->id()->toString());
        $this->assertEquals('1efaf3e4-a661-4a68-b014-669e03d1f895', $event->competitionId()->toString());
        $this->assertEquals('test event', $event->name());
        $this->assertEquals('test event description', $event->description());
        $this->assertEquals(new DateTimeImmutable('2023-01-01'), $event->date());
        $this->assertEquals(new DateTimeImmutable('2022-01-01'), $event->created()->at);
        $this->assertEquals(BaseUuid::NIL, $event->created()->by->id->toString());
        $this->assertEquals(new DateTimeImmutable('2022-01-01'), $event->updated()->at);
        $this->assertEquals(BaseUuid::NIL, $event->updated()->by->id->toString());
    }

    /** @test */
    public function it_gets_competition_by_id(): void
    {
        $this->seed(EventFakeSeeder::class);
        $event = $this->events->byId(EventId::fromString('1fc7e705-ef72-47b2-ba4e-55779b02c61f'));

        $this->assertNotNull($event);
        $this->assertEquals('1fc7e705-ef72-47b2-ba4e-55779b02c61f', $event->id()->toString());
        $this->assertEquals('1efaf3e4-a661-4a68-b014-669e03d1f895', $event->competitionId()->toString());
        $this->assertEquals('test event', $event->name());
        $this->assertEquals('test event description', $event->description());
        $this->assertEquals(new DateTimeImmutable('2023-01-01'), $event->date());
        $this->assertEquals(new DateTimeImmutable('2022-01-01'), $event->created()->at);
        $this->assertEquals(BaseUuid::NIL, $event->created()->by->id->toString());
        $this->assertEquals(new DateTimeImmutable('2022-01-01'), $event->updated()->at);
        $this->assertEquals(BaseUuid::NIL, $event->updated()->by->id->toString());
    }

    /**
     * @test
     * @dataProvider listCriteriaDataProvider
     */
    public function it_gets_list_of_events_by_criteria(Criteria $criteria, int $count, string $id, int $total): void
    {
        $this->seed(EventFakeSeeder::class);
        $result = $this->events->byCriteria($criteria);
        $events = $result->items;

        $this->assertNotNull($events);
        $this->assertIsList($events);
        $this->assertContainsOnlyInstancesOf(Event::class, $events);
        $this->assertCount($count, $events);
        $this->assertEquals($id, $events[0]->id()->toString());
        $this->assertEquals($total, $result->total);
    }
}
