<?php

namespace Sihae\Tests\Controllers;

use RKA\Session;
use Prophecy\Prophet;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Flash\Messages;
use Slim\Views\PhpRenderer;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Sihae\Controllers\LoginController;

class LoginControllerTest extends TestCase
{
    public function testShowForm()
    {
        $prophet = new Prophet();

        $renderer = $prophet->prophesize(PhpRenderer::class);
        $entityManager = $prophet->prophesize(EntityManager::class);
        $flash = $prophet->prophesize(Messages::class);

        $request = $prophet->prophesize(Request::class);
        $response = $prophet->prophesize(Response::class);

        $renderer->render($response, 'layout.phtml', ['page' => 'login'])
            ->shouldBeCalled()->willReturn($response->reveal());

        $postController = new LoginController(
            $renderer->reveal(),
            $entityManager->reveal(),
            $flash->reveal(),
            new Session()
        );

        $actual = $postController->showForm($request->reveal(), $response->reveal());

        $this->assertSame($response->reveal(), $actual);

        $prophet->checkPredictions();
    }
}
