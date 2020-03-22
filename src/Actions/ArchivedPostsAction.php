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
    private ResponseFactoryInterface $responseFactory;
    private Renderer $renderer;
    private PostRepository $repository;
    private Formatter $formatter;

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

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $posts = $this->repository->findAllOrderedByDateCreated();

        return $this->renderer->render(
            $this->responseFactory->createResponse(),
            'archive',
            [
                'archiveData' => $this->formatter->format($posts),
            ]
        );
    }
}
