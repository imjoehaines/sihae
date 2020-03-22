<?php

declare(strict_types=1);

namespace Sihae\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sihae\Renderer;

/**
 * Checks for 404s and renders the 404 page if one occurs
 */
final class NotFoundMiddleware implements MiddlewareInterface
{
    private Renderer $renderer;

    public function __construct(Renderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        if ($response->getStatusCode() === 404) {
            return $this->renderer->render($response, '404');
        }

        return $response;
    }
}
