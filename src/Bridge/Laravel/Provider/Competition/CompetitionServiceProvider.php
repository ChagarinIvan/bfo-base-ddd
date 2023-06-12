<?php

declare(strict_types=1);

namespace App\Bridge\Laravel\Provider\Competition;

use App\Domain\Competition\CompetitionRepository;
use App\Domain\Competition\Factory\CompetitionFactory;
use App\Domain\Competition\Factory\CompetitionIdGenerator;
use App\Domain\Competition\Factory\StandardCompetitionFactory;
use App\Infrastracture\Laravel\Competition\LaravelStrCompetitionIdGenerator;
use App\Infrastracture\Laravel\Eloquent\Competition\EloquentCompetitionRepository;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

final class CompetitionServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bind(CompetitionRepository::class, EloquentCompetitionRepository::class);
        $this->app->bind(CompetitionFactory::class, StandardCompetitionFactory::class);
        $this->app->bind(CompetitionIdGenerator::class, LaravelStrCompetitionIdGenerator::class);
    }
}
