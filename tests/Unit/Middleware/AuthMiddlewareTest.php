<?php

namespace Sihae\Tests\Unit\Middleware;

use RKA\Session;
use Prophecy\Prophet;
use PHPUnit\Framework\TestCase;
use Sihae\Middleware\AuthMiddleware;
use Slim\Http\Request;
use Slim\Http\Response;

class AuthMiddlewareTest extends TestCase
{
    public function tearDown()
    {
        unset($_SESSION);
    }

    public function testIt404sWhenNotLoggedIn()
    {
        $prophet = new Prophet();
        $request = $prophet->prophesize(Request::class);

        // RKA\Session is final so we can't mock it :(((((
        $_SESSION = [];
        $authMiddleware = new AuthMiddleware(new Session);

        $next = function () {
            $this->fail('$next should not be called');
        };

        $expected = 404;
        $actual = $authMiddleware($request->reveal(), new Response, $next)->getStatusCode();
    }

    public function testIt404sWhenLoggedInButNotAnAdmin()
    {
        $prophet = new Prophet();
        $request = $prophet->prophesize(Request::class);

        $_SESSION = ['user' => new class {
            public function getIsAdmin()
            {
                return false;
            }
        }];

        $authMiddleware = new AuthMiddleware(new Session);

        $next = function () {
            $this->fail('$next should not be called');
        };

        $expected = 404;
        $actual = $authMiddleware($request->reveal(), new Response, $next)->getStatusCode();
    }

    public function testItCallsNextWhenAnAdminIsLoggedIn()
    {
        $prophet = new Prophet();
        $request = $prophet->prophesize(Request::class);

        $_SESSION = ['user' => new class {
            public function getIsAdmin()
            {
                return true;
            }
        }];

        $authMiddleware = new AuthMiddleware(new Session);

        $expected = $prophet->prophesize(Response::class)->reveal();

        $next = function () use ($expected) {
            return $expected;
        };

        $actual = $authMiddleware($request->reveal(), new Response, $next);

        $this->assertSame($expected, $actual);
    }
}
