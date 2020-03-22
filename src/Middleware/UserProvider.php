<?php

declare(strict_types=1);

namespace Sihae\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RKA\Session;
use Sihae\Entities\User;
use Sihae\Renderer;
use Sihae\Repositories\UserRepository;

/**
 * Provides the logged in user to the Renderer
 */
final class UserProvider implements MiddlewareInterface
{
    private Renderer $renderer;
    private Session $session;
    private UserRepository $repository;

    public function __construct(Renderer $renderer, Session $session, UserRepository $repository)
    {
        $this->renderer = $renderer;
        $this->session = $session;
        $this->repository = $repository;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $token = $this->session->get('token');

        if (is_string($token) && $token !== '') {
            $user = $this->repository->findByToken($token);

            if ($user instanceof User) {
                Session::regenerate();
                $this->renderer->addData(['user' => $user]);
            }
        }

        return $handler->handle($request);
    }
}
