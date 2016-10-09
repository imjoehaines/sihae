<?php

namespace Sihae\Tests\Controllers;

use RKA\Session;
use Prophecy\Prophet;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Flash\Messages;
use Sihae\Entities\User;
use Slim\Views\PhpRenderer;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
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

        $loginController = new LoginController(
            $renderer->reveal(),
            $entityManager->reveal(),
            $flash->reveal(),
            new Session()
        );

        $actual = $loginController->showForm($request->reveal(), $response->reveal());

        $this->assertSame($response->reveal(), $actual);

        $prophet->checkPredictions();
    }

    public function testLoginRedirectsToHomeWhenValidLoginAttemptIsMade()
    {
        $prophet = new Prophet();

        $renderer = $prophet->prophesize(PhpRenderer::class);
        $entityManager = $prophet->prophesize(EntityManager::class);
        $flash = $prophet->prophesize(Messages::class);

        $request = $prophet->prophesize(Request::class);
        $request->getParsedBody()->shouldBeCalled()->willReturn([
            'username' => 'mattdamon69',
            'password' => 'matt damon',
        ]);

        $user = new User();
        $user->setUsername('mattdamon69');
        $user->setPassword('matt damon');

        $repository = $prophet->prophesize(EntityRepository::class);

        $entityManager->getRepository(User::class)->shouldBeCalled()->willReturn($repository->reveal());
        $repository->findOneBy(['username' => 'mattdamon69'])->shouldBeCalled()->willReturn($user);

        $entityManager->detach($user)->shouldBeCalled();
        $flash->addMessage('success', 'Welcome back mattdamon69')->shouldBeCalled();

        $loginController = new LoginController(
            $renderer->reveal(),
            $entityManager->reveal(),
            $flash->reveal(),
            new Session()
        );

        $actual = $loginController->login($request->reveal(), new Response());

        $this->assertSame(302, $actual->getStatusCode());
        $this->assertSame(['Location' => ['/']], $actual->getHeaders());

        $prophet->checkPredictions();
    }

    public function testLoginGoesBackToLoginFormWhenAnInvalidLoginAttemptIsMade()
    {
        $prophet = new Prophet();

        $renderer = $prophet->prophesize(PhpRenderer::class);
        $entityManager = $prophet->prophesize(EntityManager::class);
        $flash = $prophet->prophesize(Messages::class);

        $request = $prophet->prophesize(Request::class);
        $request->getParsedBody()->shouldBeCalled()->willReturn([
            'username' => 'mattdamon69',
            'password' => 'matt damon sucks',
        ]);

        $user = new User();
        $user->setUsername('mattdamon69');
        $user->setPassword('matt damon');

        $repository = $prophet->prophesize(EntityRepository::class);

        $entityManager->getRepository(User::class)->shouldBeCalled()->willReturn($repository->reveal());
        $repository->findOneBy(['username' => 'mattdamon69'])->shouldBeCalled()->willReturn($user);

        $response = new Response();

        $renderer->render($response, 'layout.phtml', [
            'page' => 'login',
            'errors' => ['No user was found with these credentials, please try again'],
            'username' => 'mattdamon69',
        ])->shouldBeCalled()->willReturn($response);

        $loginController = new LoginController(
            $renderer->reveal(),
            $entityManager->reveal(),
            $flash->reveal(),
            new Session()
        );

        $actual = $loginController->login($request->reveal(), $response);

        $this->assertSame(200, $actual->getStatusCode());

        $prophet->checkPredictions();
    }
}
