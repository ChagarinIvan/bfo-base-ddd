<?php

declare(strict_types=1);

namespace Tests\Bridge\Laravel\Http\Middleware;

use App\Application\Dto\Shared\TokenFootprint;
use App\Bridge\Laravel\Http\Middleware\FootprintTokenMiddleware;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class FootprintTokenMiddlewareTest extends TestCase
{
    private FootprintTokenMiddleware $middleware;

    private Container&MockObject $container;

    public function setUp(): void
    {
        $this->middleware = new FootprintTokenMiddleware(
            $this->container = $this->createMock(Container::class)
        );
    }

    /** @test */
    public function it_fails_when_request_has_no_authorisation_header(): void
    {
        $this->expectException(UnauthorizedHttpException::class);
        $this->expectExceptionMessage('No authorization header.');

        $this->container->expects($this->never())->method('instance');

        $request = new Request();
        $this->middleware->handle($request, static fn ($request) => new Response());
    }

    /** @test */
    public function it_instantiates_token_footprint_by_bearer_token(): void
    {
        $request = new Request();
        $request->headers->add(['Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI4ZWE1MDAwOC05MDQxLTQwYTktODM1MS0yYzdlY2JlMzIyYTkifQ.xW2Ln43xqo8fU1GPCBq7ZXIxbZ3UhtphUFG_wRF9Nok']);
        $tokenFootprint = new TokenFootprint();
        $tokenFootprint->id = '8ea50008-9041-40a9-8351-2c7ecbe322a9';

        $this->container
            ->expects($this->once())
            ->method('instance')
            ->with(TokenFootprint::class, $this->equalTo($tokenFootprint))
        ;

        $this->middleware->handle($request, static fn ($request) => new Response());
    }
}
