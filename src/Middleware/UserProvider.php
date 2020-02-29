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
class UserProvider implements MiddlewareInterface
{
    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @param Renderer $renderer
     * @param Session $session
     * @param UserRepository $repository
     */
    public function __construct(Renderer $renderer, Session $session, UserRepository $repository)
    {
        $this->renderer = $renderer;
        $this->session = $session;
        $this->repository = $repository;
    }

    /**
     * Provide the logged in user to the Renderer
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
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
