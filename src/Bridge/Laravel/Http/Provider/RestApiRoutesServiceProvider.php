<?php

declare(strict_types=1);

namespace App\Bridge\Laravel\Http\Provider;

use App\Bridge\Laravel\Http\Controller\Competition\CompetitionController;
use App\Bridge\Laravel\Http\Controller\Event\EventController;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

final class RestApiRoutesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Competition
        Route::get('/rest/competition/competition', [CompetitionController::class, 'list']);
        Route::get('/rest/competition/competition/{id}', [CompetitionController::class, 'view']);
        Route::middleware('token')->group(static function (): void {
            Route::post('/rest/competition/competition', [CompetitionController::class, 'create']);
            Route::put('/rest/competition/competition/{id}', [CompetitionController::class, 'changeCompetitionInfo']);
            Route::delete('/rest/competition/competition/{id}', [CompetitionController::class, 'disable']);
        });

        Route::get('/rest/event/event/{id}', [EventController::class, 'view']);
        Route::middleware('token')->group(static function (): void {
            Route::post('/rest/event/event', [EventController::class, 'create']);
            Route::put('/rest/event/event/{id}', [EventController::class, 'changeEventInfo']);
            Route::delete('/rest/event/event/{id}', [EventController::class, 'disable']);
        });
    }
}
