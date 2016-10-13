<?php

namespace Sihae\Middleware;

use Sihae\Renderer;
use Slim\Flash\Messages;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Provides flash messages to the Renderer
 */
class FlashMessageProvider
{
    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var Messages
     */
    private $flash;

    /**
     * @param Renderer $renderer
     * @param Messages $flash
     */
    public function __construct(Renderer $renderer, Messages $flash)
    {
        $this->renderer = $renderer;
        $this->flash = $flash;
    }

    /**
     * Provide any flash messages to the Renderer
     *
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return Response
     */
    public function __invoke(Request $request, Response $response, callable $next) : Response
    {
        $this->renderer->addData(['flash_messages' => $this->flash->getMessages()]);

        return $next($request, $response);
    }
}
