<?php

namespace Sihae\Middleware;

use Slim\Views\PhpRenderer;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class SettingsProvider
{
    private $renderer;
    private $settings;

    public function __construct(PhpRenderer $renderer, array $settings)
    {
        $this->renderer = $renderer;
        $this->settings = $settings;
    }

    public function __invoke(Request $request, Response $response, callable $next) : Response
    {
        $this->renderer->addAttribute('settings', $this->settings);

        return $next($request, $response);
    }
}
