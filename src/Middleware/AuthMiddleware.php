<?php

namespace Sihae\Middleware;

use RKA\Session;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

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
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
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
        if (isset($this->session->user) && $this->session->user->getIsAdmin() === true) {
            return $next($request, $response);
        }

        return $response->withStatus(404);
    }
}
