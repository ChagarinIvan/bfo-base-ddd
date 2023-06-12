<?php

declare(strict_types=1);

namespace Database\Seeders\Fakes;

use App\Infrastracture\Laravel\Eloquent\Competition\CompetitionModel;
use Illuminate\Database\Seeder;
use Tests\Faker\Competition\CompetitionFaker;

class CompetitionFakeSeeder extends Seeder
{
    public function run(): void
    {
        $competition = CompetitionFaker::fakeCompetition();
        $model = CompetitionModel::fromAggregate($competition);
        $model->save();

        $competition = CompetitionFaker::fakeCompetition(id: 'ff4d49a6-e5f0-49ea-8c5c-0bba1200aa97');
        $model = CompetitionModel::fromAggregate($competition);
        $model->disabled = true;
        $model->save();

        $competition = CompetitionFaker::fakeCompetition(id: '3a48ca7e-13bc-4198-80ba-237384dbf9a6');
        $model = CompetitionModel::fromAggregate($competition);
        $model->save();
    }
}
