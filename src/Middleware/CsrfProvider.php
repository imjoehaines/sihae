<?php

declare(strict_types=1);

namespace Sihae\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sihae\Renderer;
use Slim\Csrf\Guard;

/**
 * Provides data for CSRF protection to the Renderer
 */
final class CsrfProvider implements MiddlewareInterface
{
    /**
     * @var Renderer
     */
    private Renderer $renderer;

    /**
     * @var Guard
     */
    private Guard $csrf;

    /**
     * @param Renderer $renderer
     * @param Guard $csrf
     */
    public function __construct(Renderer $renderer, Guard $csrf)
    {
        $this->renderer = $renderer;
        $this->csrf = $csrf;
    }

    /**
     * Provide data for CSRF protection to the Renderer
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();

        $this->renderer->addData(['csrf' => [
            'nameKey' => $nameKey,
            'valueKey' => $valueKey,
            'name' => $request->getAttribute($nameKey),
            'value' => $request->getAttribute($valueKey),
        ]]);

        return $handler->handle($request);
    }
}
