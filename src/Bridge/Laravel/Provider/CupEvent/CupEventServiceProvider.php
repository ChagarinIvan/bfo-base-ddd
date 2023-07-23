<?php

declare(strict_types=1);

namespace App\Bridge\Laravel\Provider\CupEvent;

use App\Domain\CupEvent\CupEventRepository;
use App\Domain\CupEvent\Factory\CupEventFactory;
use App\Domain\CupEvent\Factory\CupEventIdGenerator;
use App\Domain\CupEvent\Factory\StandardCupEventFactory;
use App\Infrastracture\Laravel\CupEvent\LaravelStrCupEventIdGenerator;
use App\Infrastracture\Laravel\Eloquent\CupEvent\EloquentCupEventRepository;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

final class CupEventServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bind(CupEventRepository::class, EloquentCupEventRepository::class);
        $this->app->bind(CupEventFactory::class, StandardCupEventFactory::class);
        $this->app->bind(CupEventIdGenerator::class, LaravelStrCupEventIdGenerator::class);
    }
}
