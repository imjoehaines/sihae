<?php

declare(strict_types=1);

namespace Sihae\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RKA\Session;
use Sihae\Repositories\UserRepository;

/**
 * Checks the user in the current session is an admin, if they are not a 404
 * will be returned
 */
class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @param Session $session
     * @param UserRepository $repository
     * @param ResponseFactoryInterface $responseFactory
     */
    public function __construct(
        Session $session,
        UserRepository $repository,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->session = $session;
        $this->repository = $repository;
        $this->responseFactory = $responseFactory;
    }

    /**
     * Check the user in the current session is an admin, if they are not a 404
     * will be returned
     *
     * @param Request $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(Request $request, RequestHandlerInterface $handler): ResponseInterface
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
