<?php

declare(strict_types=1);

namespace Sihae\Actions;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sihae\Renderer;
use Sihae\Repositories\TagRepository;

final class TagListAction implements RequestHandlerInterface
{
    private ResponseFactoryInterface $responseFactory;
    private Renderer $renderer;
    private TagRepository $repository;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        Renderer $renderer,
        TagRepository $repository
    ) {
        $this->responseFactory = $responseFactory;
        $this->renderer = $renderer;
        $this->repository = $repository;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $tags = $this->repository->findAllOrderedByUsage();
        $response = $this->responseFactory->createResponse();

        return $this->renderer->render($response, 'tags', ['tags' => $tags]);
    }
}
