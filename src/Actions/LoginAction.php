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
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @var Session
     */
    private $session;

    /**
     * @param ResponseFactoryInterface $responseFactory
     * @param Renderer $renderer
     * @param UserRepository $repository
     * @param Session $session
     */
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

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $userDetails = $request->getParsedBody();

        $user = $this->repository->findByUsername(Safe::get('username', $userDetails, ''));

        $response = $this->responseFactory->createResponse();

        if ($user === null ||
            !$user->login(Safe::get('password', $userDetails, ''))
        ) {
            return $this->renderer->render($response, 'login', [
                'errors' => ['No user was found with these credentials, please try again'],
                'username' => Safe::get('username', $userDetails, ''),
            ]);
        }

        $this->repository->save($user);

        $this->session->set('token', $user->getToken());

        return $response->withStatus(302)->withHeader('Location', '/');
    }
}
