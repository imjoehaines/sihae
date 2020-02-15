<?php

declare(strict_types=1);

namespace Sihae\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface as Logger;
use Sihae\Renderer;
use Throwable;

class ErrorMiddleware implements MiddlewareInterface
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @param Logger $logger
     * @param ResponseFactoryInterface $responseFactory
     * @param Renderer $renderer
     */
    public function __construct(
        Logger $logger,
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
     * @param Request $request
     * @param RequestHandlerInterface $handler
     * @return Response
     */
    public function process(Request $request, RequestHandlerInterface $handler): Response
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
