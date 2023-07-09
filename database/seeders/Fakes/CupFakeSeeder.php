<?php

declare(strict_types=1);

namespace Database\Seeders\Fakes;

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
    }
}
