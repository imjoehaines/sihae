<?php

declare(strict_types=1);

namespace Sihae\Actions;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sihae\Renderer;
use Sihae\Repositories\PostRepository;

final class PostListAction implements RequestHandlerInterface
{
    private ResponseFactoryInterface $responseFactory;
    private PostRepository $postRepository;
    private Renderer $renderer;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        PostRepository $postRepository,
        Renderer $renderer
    ) {
        $this->responseFactory = $responseFactory;
        $this->postRepository = $postRepository;
        $this->renderer = $renderer;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $page = (int) $request->getAttribute('page', 1);

        $limit = 8;
        $offset = $limit * ($page - 1);

        $posts = $this->postRepository->findAllOrderedByDateCreated($limit, $offset);
        $total = $this->postRepository->count();

        return $this->renderer->render(
            $this->responseFactory->createResponse(),
            'post-list',
            [
                'posts' => $posts,
                'current_page' => $page,
                'total_pages' => (int) max(ceil($total / $limit), 1),
            ]
        );
    }
}
