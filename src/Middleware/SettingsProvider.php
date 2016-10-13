<?php

namespace Sihae\Middleware;

use Sihae\Renderer;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Provides the Sihae settings to the Renderer
 */
class SettingsProvider
{
    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var array
     */
    private $settings;

    /**
     * @param Renderer $renderer
     * @param array $settings
     */
    public function __construct(Renderer $renderer, array $settings)
    {
        $this->renderer = $renderer;
        $this->settings = $settings;
    }

    /**
     * Provide the Sihae settings to the Renderer
     *
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return Response
     */
    public function __invoke(Request $request, Response $response, callable $next) : Response
    {
        $this->renderer->addData(['settings' => $this->settings]);

        return $next($request, $response);
    }
}
