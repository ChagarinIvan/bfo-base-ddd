<?php

declare(strict_types=1);

namespace App\Bridge\Laravel\Http\Provider;

use App\Bridge\Laravel\Http\Controller\Club\ClubController;
use App\Bridge\Laravel\Http\Controller\Competition\CompetitionController;
use App\Bridge\Laravel\Http\Controller\Cup\CupController;
use App\Bridge\Laravel\Http\Controller\CupEvent\CupEventController;
use App\Bridge\Laravel\Http\Controller\Event\EventController;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

final class RestApiRoutesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::prefix('rest')->group(static function (): void {
            Route::prefix('competition')->group(static function (): void {
                Route::get('competition/{id}', [CompetitionController::class, 'view']);
                Route::get('competition', [CompetitionController::class, 'list']);
                Route::middleware('token')->group(static function (): void {
                    Route::post('competition', [CompetitionController::class, 'create']);
                    Route::put('competition/{id}', [CompetitionController::class, 'changeCompetitionInfo']);
                    Route::delete('competition/{id}', [CompetitionController::class, 'disable']);
                });
            });
            Route::prefix('event')->group(static function (): void {
                Route::get('event/{id}', [EventController::class, 'view']);
                Route::get('event', [EventController::class, 'list']);
                Route::middleware('token')->group(static function (): void {
                    Route::post('event', [EventController::class, 'create']);
                    Route::put('event/{id}', [EventController::class, 'changeEventInfo']);
                    Route::delete('event/{id}', [EventController::class, 'disable']);
                });
            });
            Route::prefix('club')->group(static function (): void {
                Route::get('club/{id}', [ClubController::class, 'view']);
                Route::get('club', [ClubController::class, 'list']);
                Route::middleware('token')->group(static function (): void {
                    Route::post('club', [ClubController::class, 'create']);
                    Route::put('club/{id}', [ClubController::class, 'changeClub']);
                    Route::delete('club/{id}', [ClubController::class, 'disable']);
                });
            });
            Route::prefix('cup')->group(static function (): void {
                Route::get('cup/{id}', [CupController::class, 'view']);
                Route::get('cup', [CupController::class, 'list']);
                Route::middleware('token')->group(static function (): void {
                    Route::post('cup', [CupController::class, 'create']);
                    Route::put('cup/{id}', [CupController::class, 'changeCupInfo']);
                    Route::delete('cup/{id}', [CupController::class, 'disable']);
                });
            });
            Route::prefix('cup-event')->group(static function (): void {
                Route::get('cup-event/{id}', [CupEventController::class, 'view']);
                Route::get('cup-event', [CupEventController::class, 'list']);
                Route::get('cup-event/{id}/calculate', [CupEventController::class, 'calculate']);
                Route::middleware('token')->group(static function (): void {
                    Route::post('cup-event', [CupEventController::class, 'create']);
                    Route::put('cup-event/{id}', [CupEventController::class, 'changeAttributes']);
                    Route::delete('cup-event/{id}', [CupEventController::class, 'disable']);
                });
            });
        });
    }
}
