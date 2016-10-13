<?php

namespace Sihae\Middleware;

use Sihae\Renderer;
use Slim\Csrf\Guard;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Provides data for CSRF protection to the Renderer
 */
class CsrfProvider
{
    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var Guard
     */
    private $csrf;

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
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return Response
     */
    public function __invoke(Request $request, Response $response, callable $next) : Response
    {
        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();

        $this->renderer->addData(['csrf' => [
            'nameKey' => $nameKey,
            'valueKey' => $valueKey,
            'name' => $request->getAttribute($nameKey),
            'value' => $request->getAttribute($valueKey),
        ]]);

        return $next($request, $response);
    }
}
