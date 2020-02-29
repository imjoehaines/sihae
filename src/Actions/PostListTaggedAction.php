<?php

declare(strict_types=1);

namespace Sihae\Actions;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sihae\Renderer;
use Sihae\Repositories\PostRepository;
use Sihae\Repositories\TagRepository;

final class PostListTaggedAction implements RequestHandlerInterface
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @param ResponseFactoryInterface $responseFactory
     * @param PostRepository $postRepository
     * @param TagRepository $tagRepository
     * @param Renderer $renderer
     */
    public function __construct(
        ResponseFactoryInterface $responseFactory,
        PostRepository $postRepository,
        TagRepository $tagRepository,
        Renderer $renderer
    ) {
        $this->responseFactory = $responseFactory;
        $this->postRepository = $postRepository;
        $this->tagRepository = $tagRepository;
        $this->renderer = $renderer;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $slug = $request->getAttribute('slug', '');

        $tag = $this->tagRepository->findBySlug($slug);

        if ($tag === null) {
            return $this->responseFactory->createResponse(404);
        }

        $page = (int) $request->getAttribute('page', 1);

        $limit = 8;
        $offset = $limit * ($page - 1);

        $posts = $this->postRepository->findAllTagged($tag->getSlug(), $limit, $offset);
        $total = $this->postRepository->countTagged($tag->getSlug());

        return $this->renderer->render(
            $this->responseFactory->createResponse(),
            'post-list',
            [
                'posts' => $posts,
                'current_page' => $page,
                'total_pages' => (int) max(ceil($total / $limit), 1),
                'tag' => $tag,
            ]
        );
    }
}
