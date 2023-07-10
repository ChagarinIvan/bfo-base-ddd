<?php

declare(strict_types=1);

namespace App\Infrastracture\Laravel\Eloquent\Cup;

use App\Domain\Cup\Cup;
use App\Domain\Cup\CupId;
use App\Domain\Cup\CupRepository;
use App\Domain\Shared\Criteria;
use App\Domain\Shared\ListingResult;
use Illuminate\Support\Collection;

final class EloquentCupRepository implements CupRepository
{
    public function add(Cup $cup): void
    {
        CupModel::fromAggregate($cup)->save();
    }

    public function lockById(CupId $id): ?Cup
    {
        /** @var CupModel|null $model */
        $model = CupModel::active()->whereId($id->toString())->lockForUpdate()->first();

        /** @var Cup|null $cup */
        $cup = $model?->toAggregate();

        return $cup;
    }

    public function byId(CupId $id): ?Cup
    {
        /** @var CupModel|null $model */
        $model = CupModel::active()->whereId($id->toString())->first();

        /** @var Cup|null $cup */
        $cup = $model?->toAggregate();

        return $cup;
    }

    public function update(Cup $cup): void
    {
        $model = CupModel::fromAggregate($cup);

        CupModel::whereId($cup->id()->toString())->update($model->toArray());
    }

    public function byCriteria(Criteria $criteria): ListingResult
    {
        $query = CupModel::active();
        $perPage = (int) $criteria->param('perPage');
        $page = (int) $criteria->param('page') - 1;

        $query->orderByDesc('createdAt');
        $query->orderByDesc('incrementalId');

        if ($criteria->hasParam('name')) {
            $query->where('name', 'LIKE', '%' . $criteria->param('name') . '%');
        }
        if ($criteria->hasParam('year')) {
            $query->where('year', $criteria->param('year'));
        }
        if ($criteria->hasParam('type')) {
            $query->where('type', $criteria->param('type'));
        }

        $count = $query->count();
        $query->limit($perPage);
        $query->offset($page * $perPage);

        $models = $query->get() ?: Collection::empty();

        /** @var Cup[] $cups */
        $cups = $models->map(static fn (CupModel $model) => $model->toAggregate())->toArray();

        return new ListingResult(
            $count,
            $cups,
        );
    }
}
