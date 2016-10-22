<?php

namespace Sihae\Middleware;

use Sihae\Renderer;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Checks for 404s and renders the 404 page if one occurs
 */
class NotFoundMiddleware
{
    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @param Renderer $renderer
     */
    public function __construct(Renderer $renderer)
    {
        $this->renderer = $renderer;
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

        if ($response->getStatusCode() === 404) {
            return $this->renderer->render($response, '404');
        }

        return $response;
    }
}
