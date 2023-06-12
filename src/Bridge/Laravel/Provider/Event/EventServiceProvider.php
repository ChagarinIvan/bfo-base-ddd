<?php

declare(strict_types=1);

namespace App\Bridge\Laravel\Provider\Event;

use App\Domain\Event\EventRepository;
use App\Domain\Event\Factory\EventFactory;
use App\Domain\Event\Factory\EventIdGenerator;
use App\Domain\Event\Factory\StandardEventFactory;
use App\Infrastracture\Laravel\Eloquent\Event\EloquentEventRepository;
use App\Infrastracture\Laravel\Event\LaravelStrEventIdGenerator;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

final class EventServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bind(EventRepository::class, EloquentEventRepository::class);
        $this->app->bind(EventFactory::class, StandardEventFactory::class);
        $this->app->bind(EventIdGenerator::class, LaravelStrEventIdGenerator::class);
    }
}
