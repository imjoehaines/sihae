<?php

namespace Sihae\Middleware;

use Slim\Csrf\Guard;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class CsrfProvider
{
    private $renderer;

    public function __construct(PhpRenderer $renderer, Guard $csrf)
    {
        $this->renderer = $renderer;
        $this->csrf = $csrf;
    }

    public function __invoke(Request $request, Response $response, callable $next) : Response
    {
        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();

        $this->renderer->addAttribute('csrf', [
            'nameKey' => $nameKey,
            'valueKey' => $valueKey,
            'name' => $request->getAttribute($nameKey),
            'value' => $request->getAttribute($valueKey),
        ]);

        return $next($request, $response);
    }
}
