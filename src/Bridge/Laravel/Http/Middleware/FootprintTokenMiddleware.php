<?php

declare(strict_types=1);

namespace App\Bridge\Laravel\Http\Middleware;

use App\Application\Dto\Shared\TokenFootprint;
use Closure;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use function base64_decode;
use function explode;
use function is_array;
use function json_decode;
use function str_replace;

final readonly class FootprintTokenMiddleware
{
    public function __construct(private Container $app)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');
        if (!$token || is_array($token)) {
            throw new UnauthorizedHttpException('Bearer', 'No authorization header.');
        }

        $token = json_decode(
            base64_decode(
                str_replace(
                    '_',
                    '/',
                    str_replace('-', '+', explode('.', $token)[1])
                )
            ),
            true
        );
        $userId = $token['userId'] ?? throw new UnauthorizedHttpException('No userId');
        $footprint = new TokenFootprint();
        $footprint->id = $userId;
        $this->app->instance(TokenFootprint::class, $footprint);

        return $next($request);
    }
}
