<?php

namespace Sihae\Tests\Controllers;

use RKA\Session;
use Prophecy\Prophet;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Flash\Messages;
use Sihae\Entities\User;
use Slim\Views\PhpRenderer;
use Sihae\Validators\Validator;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sihae\Controllers\RegistrationController;

class RegistrationControllerTest extends TestCase
{
    public function testShowFormRedirectsHomeWhenAUserIsLoggedIn()
    {
        $prophet = new Prophet();

        $renderer = $prophet->prophesize(PhpRenderer::class);
        $validator = $prophet->prophesize(Validator::class);
        $entityManager = $prophet->prophesize(EntityManager::class);
        $flash = $prophet->prophesize(Messages::class);

        $request = $prophet->prophesize(Request::class);

        $_SESSION = ['user' => ['hello']];

        $registrationController = new RegistrationController(
            $renderer->reveal(),
            $validator->reveal(),
            $entityManager->reveal(),
            $flash->reveal(),
            new Session()
        );

        $actual = $registrationController->showForm($request->reveal(), new Response());

        $this->assertSame(302, $actual->getStatusCode());
        $this->assertSame(['Location' => ['/']], $actual->getHeaders());

        $prophet->checkPredictions();
        unset($_SESSION);
    }

    public function testShowFormGoesToFormWhenNoUserIsLoggedIn()
    {
        $prophet = new Prophet();

        $renderer = $prophet->prophesize(PhpRenderer::class);
        $validator = $prophet->prophesize(Validator::class);
        $entityManager = $prophet->prophesize(EntityManager::class);
        $flash = $prophet->prophesize(Messages::class);

        $request = $prophet->prophesize(Request::class);
        $response = new Response();

        $renderer->render($response, 'layout.phtml', ['page' => 'register'])
            ->shouldBeCalled()->willReturn($response);

        $_SESSION = [];

        $registrationController = new RegistrationController(
            $renderer->reveal(),
            $validator->reveal(),
            $entityManager->reveal(),
            $flash->reveal(),
            new Session()
        );

        $actual = $registrationController->showForm($request->reveal(), $response);

        $this->assertSame(200, $actual->getStatusCode());

        $prophet->checkPredictions();
        unset($_SESSION);
    }
}
