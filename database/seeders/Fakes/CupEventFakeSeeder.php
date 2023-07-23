<?php

declare(strict_types=1);

namespace Database\Seeders\Fakes;

use App\Infrastracture\Laravel\Eloquent\CupEvent\CupEventModel;
use Illuminate\Database\Seeder;
use Tests\Faker\CupEvent\CupEventFaker;

class CupEventFakeSeeder extends Seeder
{
    public function run(): void
    {
        $cupEvent = CupEventFaker::fakeCupEvent();
        $model = CupEventModel::fromAggregate($cupEvent);
        $model->save();

        $cupEvent = CupEventFaker::fakeCupEvent(id: 'ff4d49a6-e5f0-49ea-8c5c-0bba1200aa97');
        $model = CupEventModel::fromAggregate($cupEvent);
        $model->disabled = true;
        $model->save();

        $cupEvent = CupEventFaker::fakeCupEvent(
            id: '3a48ca7e-13bc-4198-80ba-237384dbf9a6',
            cupId: 'b5f58bfd-1335-4e0c-8233-7dc2ab82181f',
        );
        $model = CupEventModel::fromAggregate($cupEvent);
        $model->save();

        $cupEvent = CupEventFaker::fakeCupEvent(
            id: '1b07ca91-1e16-4b5b-b459-341ca9e79aa9',
            eventId: 'bb3bf8fc-929b-4769-9dad-9fc147a5b87f',
        );
        $model = CupEventModel::fromAggregate($cupEvent);
        $model->save();
    }
}
