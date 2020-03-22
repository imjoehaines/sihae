<?php

declare(strict_types=1);

namespace Sihae\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Sihae\Renderer;
use Throwable;

class ErrorMiddleware implements MiddlewareInterface
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var ResponseFactoryInterface
     */
    private ResponseFactoryInterface $responseFactory;

    /**
     * @var Renderer
     */
    private Renderer $renderer;

    /**
     * @param LoggerInterface $logger
     * @param ResponseFactoryInterface $responseFactory
     * @param Renderer $renderer
     */
    public function __construct(
        LoggerInterface $logger,
        ResponseFactoryInterface $responseFactory,
        Renderer $renderer
    ) {
        $this->logger = $logger;
        $this->responseFactory = $responseFactory;
        $this->renderer = $renderer;
    }

    /**
     * Handle any error that may occur from $handler by logging it and rendering the error page
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $exception) {
            $this->logger->critical(
                $exception->getMessage(),
                [
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ]
            );
        }

        return $this->renderer->render(
            $this->responseFactory->createResponse(500),
            'error'
        );
    }
}
