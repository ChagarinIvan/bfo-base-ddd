<?php

declare(strict_types=1);

namespace App\Infrastracture\Laravel\Eloquent\CupEvent;

use App\Domain\CupEvent\CupEvent;
use App\Domain\CupEvent\CupEventId;
use App\Domain\CupEvent\CupEventRepository;
use App\Domain\Shared\Criteria;
use App\Domain\Shared\ListingResult;
use Illuminate\Support\Collection;

final class EloquentCupEventRepository implements CupEventRepository
{
    public function add(CupEvent $cupEvent): void
    {
        CupEventModel::fromAggregate($cupEvent)->save();
    }

    public function lockById(CupEventId $id): ?CupEvent
    {
        /** @var CupEventModel|null $model */
        $model = CupEventModel::active()->whereId($id->toString())->lockForUpdate()->first();

        /** @var CupEvent|null $cupEvent */
        $cupEvent = $model?->toAggregate();

        return $cupEvent;
    }

    public function byId(CupEventId $id): ?CupEvent
    {
        /** @var CupEventModel|null $model */
        $model = CupEventModel::active()->whereId($id->toString())->first();

        /** @var CupEvent|null $cupEvent */
        $cupEvent = $model?->toAggregate();

        return $cupEvent;
    }

    public function update(CupEvent $cupEvent): void
    {
        $model = CupEventModel::fromAggregate($cupEvent);

        CupEventModel::whereId($cupEvent->id()->toString())->update($model->toArray());
    }

    public function oneByCriteria(Criteria $criteria): ?CupEvent
    {
        $builder = CupEventModel::active();
        foreach ($criteria->params() as $param => $value) {
            $builder = $builder->where($param, $value);
        }

        /** @var CupEventModel|null $model */
        $model = $builder->first();
        /** @var CupEvent|null $club */
        $club = $model?->toAggregate();

        return $club;
    }

    public function byCriteria(Criteria $criteria): ListingResult
    {
        $query = CupEventModel::active();
        $perPage = (int) $criteria->param('perPage');
        $page = (int) $criteria->param('page') - 1;

        $query->orderByDesc('createdAt');
        $query->orderByDesc('incrementalId');

        foreach ($criteria->withoutParams(['perPage', 'page']) as $param => $value) {
            $query->where($param, $value);
        }

        $count = $query->count();
        $query->limit($perPage);
        $query->offset($page * $perPage);

        $models = $query->get() ?: Collection::empty();

        /** @var CupEvent[] $cupsEvents */
        $cupsEvents = $models->map(static fn (CupEventModel $model) => $model->toAggregate())->toArray();

        return new ListingResult(
            $count,
            $cupsEvents,
        );
    }
}
