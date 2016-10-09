<?php

namespace Sihae\Middleware;

use Slim\Flash\Messages;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Provides flash messages to the PhpRenderer
 */
class FlashMessageProvider
{
    /**
     * @var PhpRenderer
     */
    private $renderer;

    /**
     * @var Messages
     */
    private $flash;

    /**
     * @param PhpRenderer $renderer
     * @param Messages $flash
     */
    public function __construct(PhpRenderer $renderer, Messages $flash)
    {
        $this->renderer = $renderer;
        $this->flash = $flash;
    }

    /**
     * Provide any flash messages to the PhpRenderer
     *
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return Response
     */
    public function __invoke(Request $request, Response $response, callable $next) : Response
    {
        $this->renderer->addAttribute('flash_messages', $this->flash->getMessages());

        return $next($request, $response);
    }
}
