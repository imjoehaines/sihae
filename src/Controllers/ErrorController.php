<?php

namespace Sihae\Controllers;

use Throwable;
use Sihae\Renderer;
use Psr\Log\LoggerInterface as Logger;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ErrorController
{
    public function __construct(
        Logger $logger,
        Response $response,
        Renderer $renderer
    ) {
        $this->logger = $logger;
        $this->response = $response;
        $this->renderer = $renderer;
    }

    public function __invoke(Request $request, Response $response, Throwable $exception) : Response
    {
        try {
            $this->logger->critical(
                $exception->getMessage(),
                [
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ]
            );
        } catch (Throwable $e) {
            // this page intentionally left blank
        }

        return $this->renderer->render(
            $this->response->withStatus(500),
            'error'
        );
    }
}
