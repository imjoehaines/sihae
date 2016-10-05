<?php

namespace Sihae\Middleware;

use RKA\Session;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class SessionProvider
{
    private $renderer;

    public function __construct(PhpRenderer $renderer, Session $session)
    {
        $this->renderer = $renderer;
        $this->session = $session;
    }

    public function __invoke(Request $request, Response $response, callable $next) : Response
    {
        $this->renderer->addAttribute('session', $this->session);

        return $next($request, $response);
    }
}
