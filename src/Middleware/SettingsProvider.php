<?php

namespace Sihae\Middleware;

use Slim\Views\PhpRenderer;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Provides the Sihae settings to the PhpRenderer
 */
class SettingsProvider
{
    /**
     * @var PhpRenderer
     */
    private $renderer;

    /**
     * @var array
     */
    private $settings;

    /**
     * @param PhpRenderer $renderer
     * @param array $settings
     */
    public function __construct(PhpRenderer $renderer, array $settings)
    {
        $this->renderer = $renderer;
        $this->settings = $settings;
    }

    /**
     * Provide the Sihae settings to the PhpRenderer
     *
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return Response
     */
    public function __invoke(Request $request, Response $response, callable $next) : Response
    {
        $this->renderer->addAttribute('settings', $this->settings);

        return $next($request, $response);
    }
}
