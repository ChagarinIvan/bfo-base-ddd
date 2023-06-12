<?php

declare(strict_types=1);

namespace App\Bridge\Laravel\Provider\Club;

use App\Domain\Club\ClubRepository;
use App\Domain\Club\Factory\ClubFactory;
use App\Domain\Club\Factory\ClubIdGenerator;
use App\Domain\Club\Factory\NormalizeClubNameClubFactory;
use App\Domain\Club\Factory\PreventDuplicateClubFactory;
use App\Domain\Club\Factory\StandardClubFactory;
use App\Domain\Shared\Normalizer\Normalizer;
use App\Infrastracture\Laravel\Club\LaravelStrClubIdGenerator;
use App\Infrastracture\Laravel\Eloquent\Club\EloquentClubRepository;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

final class ClubServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bind(ClubRepository::class, EloquentClubRepository::class);
        $this->app->bind(StandardClubFactory::class, StandardClubFactory::class);
        $this->app->bind(PreventDuplicateClubFactory::class, fn () => new PreventDuplicateClubFactory(
            $this->app->get(StandardClubFactory::class),
            $this->app->get(ClubRepository::class),
        ));
        $this->app->bind(ClubFactory::class, fn () => new NormalizeClubNameClubFactory(
            $this->app->get(PreventDuplicateClubFactory::class),
            $this->app->get(Normalizer::class),
        ));
        $this->app->bind(ClubIdGenerator::class, LaravelStrClubIdGenerator::class);
    }
}
