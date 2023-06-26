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

        $event = EventFaker::fakeEvent(
            '1efaf3e4-a661-4a68-b014-669e03d1f895',
            'ff4d49a6-e5f0-49ea-8c5c-0bba1200aa97',
            'test event 1',
            'test event description 1',
            '2023-02-01'
        );
        $model = EventModel::fromAggregate($event);
        $model->save();
    }
}
