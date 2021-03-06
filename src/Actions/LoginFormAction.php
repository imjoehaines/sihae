<?php

declare(strict_types=1);

namespace Sihae\Actions;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sihae\Renderer;

final class LoginFormAction implements RequestHandlerInterface
{
    private ResponseFactoryInterface $responseFactory;
    private Renderer $renderer;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        Renderer $renderer
    ) {
        $this->responseFactory = $responseFactory;
        $this->renderer = $renderer;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->renderer->render(
            $this->responseFactory->createResponse(),
            'login'
        );
    }
}
