<?php

declare(strict_types=1);

namespace App\Bridge\Laravel\Http;

use App\Bridge\Laravel\Http\Middleware\CorsMiddleware;
use App\Bridge\Laravel\Http\Middleware\FootprintTokenMiddleware;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /** @var array<int, string> */
    protected $middleware = [
        CorsMiddleware::class,
    ];

    /** @var array<string, array<int, string>> */
    protected $middlewareGroups = [
        'token' => [
            FootprintTokenMiddleware::class,
        ],
    ];
}
