<?php

declare(strict_types=1);

namespace Sihae\Actions;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sihae\Formatters\Formatter;
use Sihae\Renderer;
use Sihae\Repositories\PostRepository;

final class ArchivedPostsAction implements RequestHandlerInterface
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
     * @var PostRepository
     */
    private $repository;

    /**
     * @var Formatter
     */
    private $formatter;

    /**
     * @param ResponseFactoryInterface $responseFactory
     * @param Renderer $renderer
     * @param PostRepository $repository
     * @param Formatter $formatter
     */
    public function __construct(
        ResponseFactoryInterface $responseFactory,
        Renderer $renderer,
        PostRepository $repository,
        Formatter $formatter
    ) {
        $this->responseFactory = $responseFactory;
        $this->renderer = $renderer;
        $this->repository = $repository;
        $this->formatter = $formatter;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $posts = $this->repository->findAllOrderedByDateCreated();
        $response = $this->responseFactory->createResponse();

        return $this->renderer->render($response, 'archive', [
            'archiveData' => $this->formatter->format($posts),
        ]);
    }
}
