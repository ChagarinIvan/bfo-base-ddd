<?php

declare(strict_types=1);

namespace App\Infrastracture\Laravel\Eloquent\Competition;

use App\Domain\Competition\Competition;
use App\Domain\Competition\CompetitionId;
use App\Domain\Competition\CompetitionRepository;

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
}
