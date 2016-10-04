<?php

namespace Sihae\Middleware;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class NotFoundMiddleware
{
    public function __construct(callable $notFoundHandler)
    {
        $this->notFoundHandler = $notFoundHandler;
    }

    public function __invoke(Request $request, Response $response, callable $next) : Response
    {
        $response = $next($request, $response);

        if ($response->getStatusCode() == 404) {
            return ($this->notFoundHandler)($request, $response);
        }

        return $response;
    }
}
