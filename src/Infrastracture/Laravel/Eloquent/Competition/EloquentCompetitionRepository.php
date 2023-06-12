<?php

declare(strict_types=1);

namespace App\Infrastracture\Laravel\Eloquent\Competition;

use App\Domain\Competition\Competition;
use App\Domain\Competition\CompetitionId;
use App\Domain\Competition\CompetitionRepository;
use App\Domain\Shared\Criteria;
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

    public function byCriteria(Criteria $criteria): array
    {
        $query = CompetitionModel::active();
        if ($criteria->hasParams(['perPage', 'page'])) {
            $perPage = (int) $criteria->param('perPage');
            $page = (int) $criteria->param('page');
            $query->limit($perPage);
            $query->offset($page * $perPage);
        }

        $models = $query->get() ?: Collection::empty();

        /** @var Competition[] $competitions */
        $competitions = $models->map(static fn (CompetitionModel $model) => $model->toAggregate())->toArray();

        return $competitions;
    }
}
