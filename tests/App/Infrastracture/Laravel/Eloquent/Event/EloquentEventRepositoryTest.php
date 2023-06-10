<?php

declare(strict_types=1);

namespace Tests\Infrastracture\Laravel\Eloquent\Event;

use App\Domain\Event\EventId;
use App\Infrastracture\Laravel\Eloquent\Event\EloquentEventRepository;
use Database\Seeders\Fakes\EventFakeSeeder;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Ramsey\Uuid\Uuid as BaseUuid;
use Tests\Faker\Event\EventFaker;
use Tests\TestCase;

class EloquentEventRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private EloquentEventRepository $events;

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
}
