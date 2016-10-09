<?php

namespace Sihae\Middleware;

use Slim\Csrf\Guard;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class CsrfProvider
{
    /**
     * @var PhpRenderer
     */
    private $renderer;

    /**
     * @var Guard
     */
    private $csrf;

    /**
     * @param PhpRenderer $renderer
     * @param Guard $csrf
     */
    public function __construct(PhpRenderer $renderer, Guard $csrf)
    {
        $this->renderer = $renderer;
        $this->csrf = $csrf;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return Response
     */
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
