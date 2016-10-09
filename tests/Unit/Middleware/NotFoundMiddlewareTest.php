<?php

namespace Sihae\Tests\Unit\Middleware;

use Prophecy\Prophet;
use Slim\Http\Request;
use Slim\Http\Response;
use PHPUnit\Framework\TestCase;
use Sihae\Middleware\NotFoundMiddleware;

class NotFoundMiddlewareTest extends TestCase
{
    public function testItDoesNothingWhenA200CodeIsReturned()
    {
        $prophet = new Prophet();
        $request = $prophet->prophesize(Request::class);

        $notFoundHandler = function ($request, $response) {
            return $response;
        };

        $notFoundMiddleware = new NotFoundMiddleware($notFoundHandler);

        $next = function ($request, $response) {
            return $response;
        };

        $response = new Response();
        $actual = $notFoundMiddleware($request->reveal(), $response, $next);

        $this->assertSame($response, $actual);
    }

    public function testItDoesNothingWhenA500CodeIsReturned()
    {
        $prophet = new Prophet();
        $request = $prophet->prophesize(Request::class);

        $notFoundHandler = function ($request, $response) {
            return $response;
        };

        $notFoundMiddleware = new NotFoundMiddleware($notFoundHandler);

        $next = function ($request, $response) {
            return $response->withStatus(500);
        };

        $response = new Response();
        $actual = $notFoundMiddleware($request->reveal(), $response, $next);

        $expected = (new Response())->withStatus(500);

        $this->assertSame($expected->getStatusCode(), $actual->getStatusCode());
    }

    public function testItCallsNotFoundHandlerWhenA404Occurs()
    {
        $prophet = new Prophet();
        $request = $prophet->prophesize(Request::class);

        $notFoundHandler = function ($request, $response) {
            $body = $response->getBody();
            $body->write('hello');

            return $response;
        };

        $notFoundMiddleware = new NotFoundMiddleware($notFoundHandler);

        $next = function ($request, $response) {
            return $response->withStatus(404);
        };

        $response = new Response();
        $actual = $notFoundMiddleware($request->reveal(), $response, $next);

        $this->assertSame('hello', (string) $actual->getBody());
    }
}
