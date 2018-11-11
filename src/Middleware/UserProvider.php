<?php declare(strict_types=1);

namespace Sihae\Middleware;

use RKA\Session;
use Sihae\Renderer;
use Sihae\Repositories\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Provides the logged in user to the Renderer
 */
class UserProvider
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
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return Response
     */
    public function __invoke(Request $request, Response $response, callable $next) : Response
    {
        $token = $this->session->get('token');

        if ($token) {
            $user = $this->repository->findByToken($token);

            if ($user) {
                Session::regenerate();
                $this->renderer->addData(['user' => $user]);
            }
        }

        return $next($request, $response);
    }
}
