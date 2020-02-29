<?php

namespace Sihae\Actions;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RKA\Session;
use Sihae\Renderer;

final class RegistrationFormAction implements RequestHandlerInterface
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var Session
     */
    private $session;

    /**
     * @param Renderer $renderer
     * @param Session $session
     * @param ResponseFactoryInterface $responseFactory
     */
    public function __construct(
        ResponseFactoryInterface $responseFactory,
        Renderer $renderer,
        Session $session
    ) {
        $this->responseFactory = $responseFactory;
        $this->renderer = $renderer;
        $this->session = $session;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->session->get('token')) {
            return $this->responseFactory->createResponse(302)
                ->withHeader('Location', '/');
        }

        return $this->renderer->render(
            $this->responseFactory->createResponse(),
            'register'
        );
    }
}
