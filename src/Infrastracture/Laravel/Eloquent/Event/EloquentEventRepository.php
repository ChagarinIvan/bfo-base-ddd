<?php

declare(strict_types=1);

namespace App\Infrastracture\Laravel\Eloquent\Event;

use App\Domain\Event\Event;
use App\Domain\Event\EventId;
use App\Domain\Event\EventRepository;

final class EloquentEventRepository implements EventRepository
{
    public function add(Event $event): void
    {
        EventModel::fromAggregate($event)->save();
    }

    public function lockById(EventId $id): ?Event
    {
        /** @var EventModel|null $model */
        $model = EventModel::active()
            ->whereId($id->toString())
            ->lockForUpdate()
            ->first()
        ;

        /** @var Event|null $event */
        $event = $model?->toAggregate();

        return $event;
    }

    public function byId(EventId $id): ?Event
    {
        /** @var EventModel|null $model */
        $model = EventModel::active()
            ->whereId($id->toString())
            ->first()
        ;

        /** @var Event|null $event */
        $event = $model?->toAggregate();

        return $event;
    }

    public function update(Event $event): void
    {
        $model = EventModel::fromAggregate($event);

        EventModel::whereId($event->id()->toString())->update($model->toArray());
    }
}
