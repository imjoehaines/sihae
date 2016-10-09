<?php

namespace Sihae\Tests\Unit\Middleware;

use RKA\Session;
use Prophecy\Prophet;
use Prophecy\Argument;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\PhpRenderer;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManager;
use Sihae\Middleware\UserProvider;

class UserProviderTest extends TestCase
{
    public function tearDown()
    {
        unset($_SESSION);
    }

    public function testItDoesNothingIfThereIsNoLoggedInUser()
    {
        $prophet = new Prophet();
        $request = $prophet->prophesize(Request::class);
        $renderer = $prophet->prophesize(PhpRenderer::class);
        $entityManager = $prophet->prophesize(EntityManager::class);

        $renderer->addAttribute(Argument::any())->shouldNotBeCalled();
        $entityManager->merge(Argument::any())->shouldNotBeCalled();

        $_SESSION = [];
        $userProvider = new UserProvider($renderer->reveal(), new Session(), $entityManager->reveal());

        $next = function ($request, $response) {
            return $response;
        };

        $actual = $userProvider($request->reveal(), new Response(), $next);

        $prophet->checkPredictions();

        $this->assertInstanceOf(Response::class, $actual);
    }

    public function testItProvidesTheLoggedInUserToTheRenderer()
    {
        $prophet = new Prophet();
        $request = $prophet->prophesize(Request::class);
        $renderer = $prophet->prophesize(PhpRenderer::class);
        $entityManager = $prophet->prophesize(EntityManager::class);
        $user = 'Amy';

        $renderer->addAttribute('user', $user)->shouldBeCalled();
        $entityManager->merge($user)->shouldBeCalled()->willReturnArgument(0);

        $_SESSION = ['user' => $user];
        $userProvider = new UserProvider($renderer->reveal(), new Session(), $entityManager->reveal());

        $next = function ($request, $response) {
            return $response;
        };

        $actual = $userProvider($request->reveal(), new Response(), $next);

        $prophet->checkPredictions();

        $this->assertInstanceOf(Response::class, $actual);
    }
}
