<?php

declare(strict_types=1);

namespace Database\Seeders\Fakes;

use App\Infrastracture\Laravel\Eloquent\Club\ClubModel;
use Illuminate\Database\Seeder;
use Tests\Faker\Club\ClubFaker;

class ClubFakeSeeder extends Seeder
{
    public function run(): void
    {
        ClubModel::fromAggregate(ClubFaker::fakeClub())->save();
        ClubModel::fromAggregate(ClubFaker::fakeClub(id: 'ab79834f-494b-4137-9280-eb496328addf', name: 'test club 3'))->save();
        ClubModel::fromAggregate(ClubFaker::fakeClub(id: 'a25c7b35-dae6-4d92-8f62-d3dcdfa22f51', name: 'test club 4'))->save();
    }
}
