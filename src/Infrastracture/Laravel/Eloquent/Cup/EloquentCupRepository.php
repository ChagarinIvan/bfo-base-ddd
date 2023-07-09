<?php

declare(strict_types=1);

namespace App\Infrastracture\Laravel\Eloquent\Cup;

use App\Domain\Cup\Cup;
use App\Domain\Cup\CupId;
use App\Domain\Cup\CupRepository;

final class EloquentCupRepository implements CupRepository
{
    public function add(Cup $cup): void
    {
        CupModel::fromAggregate($cup)->save();
    }

    public function byId(CupId $id): ?Cup
    {
        /** @var CupModel|null $model */
        $model = CupModel::active()
            ->whereId($id->toString())
            ->first()
        ;

        /** @var Cup|null $cup */
        $cup = $model?->toAggregate();

        return $cup;
    }
}
