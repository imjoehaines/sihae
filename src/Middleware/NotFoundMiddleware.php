<?php

namespace Sihae\Middleware;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Checks for 404s and passes them on to the notFoundHandler if one occurs
 */
class NotFoundMiddleware
{
    /**
     * @var callable
     */
    private $notFoundHandler;

    /**
     * @param callable $notFoundHandler
     */
    public function __construct(callable $notFoundHandler)
    {
        $this->notFoundHandler = $notFoundHandler;
    }

    /**
     * Check for 404s and pass them on to the notFoundHandler if one occurs
     *
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return Response
     */
    public function __invoke(Request $request, Response $response, callable $next) : Response
    {
        $response = $next($request, $response);

        if ($response->getStatusCode() == 404) {
            return ($this->notFoundHandler)($request, $response);
        }

        return $response;
    }
}
