<?php

declare(strict_types=1);

namespace Database\Seeders\Fakes;

use App\Domain\Cup\CupType;
use App\Infrastracture\Laravel\Eloquent\Cup\CupModel;
use Illuminate\Database\Seeder;
use Tests\Faker\Cup\CupFaker;

class CupFakeSeeder extends Seeder
{
    public function run(): void
    {
        $cup = CupFaker::fakeCup();
        $model = CupModel::fromAggregate($cup);
        $model->save();

        $cup = CupFaker::fakeCup(id: 'ff4d49a6-e5f0-49ea-8c5c-0bba1200aa97');
        $model = CupModel::fromAggregate($cup);
        $model->disabled = true;
        $model->save();

        $cup = CupFaker::fakeCup(id: '3a48ca7e-13bc-4198-80ba-237384dbf9a6', name: 'cup 12');
        $model = CupModel::fromAggregate($cup);
        $model->save();

        $cup = CupFaker::fakeCup(
            id: '1b07ca91-1e16-4b5b-b459-341ca9e79aa9',
            name: 'cup name 1',
            year: 2021,
            type: CupType::BIKE,
        );
        $model = CupModel::fromAggregate($cup);
        $model->save();
    }
}
