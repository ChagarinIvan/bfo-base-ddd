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
        $competition = CompetitionFaker::fakeCompetition(from: '2023-02-01', to: '2023-02-02');
        $model = CompetitionModel::fromAggregate($competition);
        $model->save();

        $competition = CompetitionFaker::fakeCompetition(id: 'ff4d49a6-e5f0-49ea-8c5c-0bba1200aa97');
        $model = CompetitionModel::fromAggregate($competition);
        $model->disabled = true;
        $model->save();

        $competition = CompetitionFaker::fakeCompetition(id: '3a48ca7e-13bc-4198-80ba-237384dbf9a6', description: 'test competition description 1');
        $model = CompetitionModel::fromAggregate($competition);
        $model->save();

        $competition = CompetitionFaker::fakeCompetition(
            id: '1b07ca91-1e16-4b5b-b459-341ca9e79aa9',
            name: 'competition name 1',
            description: 'competition description',
            from: '2023-02-01',
            to: '2023-02-02',
        );
        $model = CompetitionModel::fromAggregate($competition);
        $model->save();
    }
}
