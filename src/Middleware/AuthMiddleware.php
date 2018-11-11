<?php declare(strict_types=1);

namespace Sihae\Middleware;

use RKA\Session;
use Sihae\Repositories\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Checks the user in the current session is an admin, if they are not a 404
 * will be returned
 */
class AuthMiddleware
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
     * @param Session $session
     * @param UserRepository $repository
     */
    public function __construct(Session $session, UserRepository $repository)
    {
        $this->session = $session;
        $this->repository = $repository;
    }

    /**
     * Check the user in the current session is an admin, if they are not a 404
     * will be returned
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

            if ($user && $user->isAdmin() === true) {
                return $next($request->withAttribute('user', $user), $response);
            }
        }

        return $response->withStatus(404);
    }
}
