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
        if (empty($this->session->get('username'))) {
            return $response->withStatus(404);
        }

        return $next($request, $response);
    }
}
