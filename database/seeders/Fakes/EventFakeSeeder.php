<?php

declare(strict_types=1);

namespace Database\Seeders\Fakes;

use App\Infrastracture\Laravel\Eloquent\Event\EventModel;
use Illuminate\Database\Seeder;
use Tests\Faker\Event\EventFaker;

class EventFakeSeeder extends Seeder
{
    public function run(): void
    {
        $event = EventFaker::fakeEvent();
        $model = EventModel::fromAggregate($event);
        $model->save();

        $event = EventFaker::fakeEvent(id: 'ff4d49a6-e5f0-49ea-8c5c-0bba1200aa97');
        $model = EventModel::fromAggregate($event);
        $model->disabled = true;
        $model->save();
    }
}
