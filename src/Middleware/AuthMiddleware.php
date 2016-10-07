<?php

namespace Sihae\Middleware;

use RKA\Session;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class AuthMiddleware
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function __invoke(Request $request, Response $response, callable $next) : Response
    {
        if (isset($this->session->user) && $this->session->user->getIsAdmin() === true) {
            return $next($request, $response);
        }

        return $response->withStatus(404);
    }
}
