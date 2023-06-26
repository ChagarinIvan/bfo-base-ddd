<?php

declare(strict_types=1);

namespace App\Infrastracture\Laravel\Eloquent\Competition;

use App\Domain\Competition\Competition;
use App\Domain\Competition\CompetitionId;
use App\Domain\Competition\CompetitionRepository;
use App\Domain\Shared\Criteria;
use App\Domain\Shared\ListingResult;
use Illuminate\Support\Collection;

final class EloquentCompetitionRepository implements CompetitionRepository
{
    public function add(Competition $competition): void
    {
        CompetitionModel::fromAggregate($competition)->save();
    }

    public function lockById(CompetitionId $id): ?Competition
    {
        /** @var CompetitionModel|null $model */
        $model = CompetitionModel::active()
            ->whereId($id->toString())
            ->lockForUpdate()
            ->first()
        ;

        /** @var Competition|null $competition */
        $competition = $model?->toAggregate();

        return $competition;
    }

    public function update(Competition $competition): void
    {
        $model = CompetitionModel::fromAggregate($competition);

        CompetitionModel::whereId($competition->id()->toString())->update($model->toArray());
    }

    public function byId(CompetitionId $id): ?Competition
    {
        /** @var CompetitionModel|null $model */
        $model = CompetitionModel::active()
            ->whereId($id->toString())
            ->first()
        ;

        /** @var Competition|null $competition */
        $competition = $model?->toAggregate();

        return $competition;
    }

    public function byCriteria(Criteria $criteria): ListingResult
    {
        $query = CompetitionModel::active();
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
        if ($criteria->hasParam('from')) {
            $query->where('from', $criteria->param('from'));
        }
        if ($criteria->hasParam('to')) {
            $query->where('to', $criteria->param('to'));
        }

        $count = $query->count();
        $query->limit($perPage);
        $query->offset($page * $perPage);

        $models = $query->get() ?: Collection::empty();

        /** @var Competition[] $competitions */
        $competitions = $models->map(static fn (CompetitionModel $model) => $model->toAggregate())->toArray();

        return new ListingResult(
            $count,
            $competitions,
        );
    }
}
