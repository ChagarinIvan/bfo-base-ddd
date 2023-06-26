<?php

declare(strict_types=1);

namespace App\Infrastracture\Laravel\Eloquent\Event;

use App\Domain\Event\Event;
use App\Domain\Event\EventId;
use App\Domain\Event\EventRepository;
use App\Domain\Shared\Criteria;
use App\Domain\Shared\ListingResult;
use Illuminate\Support\Collection;

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

    public function byCriteria(Criteria $criteria): ListingResult
    {
        $query = EventModel::active();
        $perPage = (int) $criteria->param('perPage');
        $page = (int) $criteria->param('page') - 1;

        $query->orderByDesc('createdAt');
        $query->orderByDesc('incrementalId');

        if ($criteria->hasParam('name')) {
            $query->where('name', 'LIKE', '%' . $criteria->param('name') . '%');
        }
        if ($criteria->hasParam('description')) {
            $query->where('description', 'LIKE', '%' . $criteria->param('description') . '%');
        }
        if ($criteria->hasParam('date')) {
            $query->where('date', $criteria->param('date'));
        }
        if ($criteria->hasParam('competitionId')) {
            $query->where('competitionId', $criteria->param('competitionId'));
        }

        $count = $query->count();
        $query->limit($perPage);
        $query->offset($page * $perPage);

        $models = $query->get() ?: Collection::empty();

        /** @var Event[] $events */
        $events = $models->map(static fn (EventModel $model) => $model->toAggregate())->toArray();

        return new ListingResult(
            $count,
            $events,
        );
    }
}
