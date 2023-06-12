<?php

declare(strict_types=1);

namespace App\Bridge\Laravel\Provider;

use App\Domain\Shared\ActualClock;
use App\Domain\Shared\Clock;
use App\Domain\Shared\TransactionManager;
use App\Infrastracture\Laravel\Eloquent\Shared\EloquentTransactionalManager;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bind(Clock::class, ActualClock::class);
        $this->app->bind(TransactionManager::class, EloquentTransactionalManager::class);
    }
}
