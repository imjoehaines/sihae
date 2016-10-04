<?php

namespace Sihae\Middleware;

use Slim\Flash\Messages;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class FlashMessageProvider
{
    private $renderer;
    private $flash;

    public function __construct(PhpRenderer $renderer, Messages $flash)
    {
        $this->renderer = $renderer;
        $this->flash = $flash;
    }

    public function __invoke(Request $request, Response $response, callable $next) : Response
    {
        $this->renderer->addAttribute('flash_messages', $this->flash->getMessages());

        return $next($request, $response);
    }
}
