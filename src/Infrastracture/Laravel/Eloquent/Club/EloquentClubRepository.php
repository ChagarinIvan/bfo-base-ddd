<?php

declare(strict_types=1);

namespace App\Infrastracture\Laravel\Eloquent\Club;

use App\Domain\Club\Club;
use App\Domain\Club\ClubId;
use App\Domain\Club\ClubRepository;
use App\Domain\Shared\Criteria;

final class EloquentClubRepository implements ClubRepository
{
    public function add(Club $club): void
    {
        ClubModel::fromAggregate($club)->save();
    }

    public function lockById(ClubId $id): ?Club
    {
        /** @var ClubModel|null $model */
        $model = ClubModel::active()
            ->whereId($id->toString())
            ->lockForUpdate()
            ->first()
        ;

        /** @var Club|null $club */
        $club = $model?->toAggregate();

        return $club;
    }

    public function update(Club $club): void
    {
        $model = ClubModel::fromAggregate($club);

        ClubModel::whereId($club->id()->toString())->update($model->toArray());
    }

    public function oneByCriteria(Criteria $criteria): ?Club
    {
        $builder = ClubModel::active();
        foreach ($criteria->params() as $param => $value) {
            $builder = $builder->where($param, $value);
        }

        /** @var ClubModel|null $model */
        $model = $builder->first();
        /** @var Club|null $club */
        $club = $model?->toAggregate();

        return $club;
    }
}
