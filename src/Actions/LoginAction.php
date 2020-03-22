<?php

declare(strict_types=1);

namespace Sihae\Actions;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RKA\Session;
use Sihae\Renderer;
use Sihae\Repositories\UserRepository;
use Sihae\Utils\Safe;

final class LoginAction implements RequestHandlerInterface
{
    private ResponseFactoryInterface $responseFactory;
    private Renderer $renderer;
    private UserRepository $repository;
    private Session $session;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        Renderer $renderer,
        UserRepository $repository,
        Session $session
    ) {
        $this->responseFactory = $responseFactory;
        $this->renderer = $renderer;
        $this->repository = $repository;
        $this->session = $session;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $userDetails = $request->getParsedBody();

        $username = Safe::getString('username', $userDetails, '');
        $password = Safe::getString('password', $userDetails, '');

        $user = $this->repository->findByUsername($username);

        $response = $this->responseFactory->createResponse();

        if (
            $user === null ||
            !$user->login($password)
        ) {
            return $this->renderer->render(
                $response,
                'login',
                [
                    'errors' => ['No user was found with these credentials, please try again'],
                    'username' => $username,
                ]
            );
        }

        $this->repository->save($user);

        $this->session->set('token', $user->getToken());

        return $response->withStatus(302)->withHeader('Location', '/');
    }
}
