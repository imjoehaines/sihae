<?php

declare(strict_types=1);

namespace Sihae\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RKA\Session;
use Sihae\Repositories\UserRepository;

/**
 * Checks the user in the current session is an admin, if they are not a 404
 * will be returned
 */
final class AuthMiddleware implements MiddlewareInterface
{
    private Session $session;
    private UserRepository $repository;
    private ResponseFactoryInterface $responseFactory;

    public function __construct(
        Session $session,
        UserRepository $repository,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->session = $session;
        $this->repository = $repository;
        $this->responseFactory = $responseFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $token = $this->session->get('token');

        if ($token) {
            $user = $this->repository->findByToken($token);

            if ($user !== null && $user->isAdmin() === true) {
                return $handler->handle($request->withAttribute('user', $user));
            }
        }

        return $this->responseFactory->createResponse(404);
    }
}
