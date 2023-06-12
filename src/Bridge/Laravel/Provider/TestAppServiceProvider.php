<?php

declare(strict_types=1);

namespace App\Bridge\Laravel\Provider;

use App\Domain\Shared\Clock;
use App\Domain\Shared\FrozenClock;
use DateTimeImmutable;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class TestAppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->environment() === 'testing') {
            $this->app->bind(Clock::class, static fn () => new FrozenClock(new DateTimeImmutable('2023-03-03')));
        }
    }
}
