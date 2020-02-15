<?php

declare(strict_types=1);

namespace Sihae\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Sihae\Renderer;
use Sihae\Repositories\PostRepository;
use Sihae\Repositories\TagRepository;

/**
 * Controller for handling showing multiple blog posts
 */
class PostListController
{
    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * @param Renderer $renderer
     * @param PostRepository $postRepository
     * @param TagRepository $tagRepository
     */
    public function __construct(
        Renderer $renderer,
        PostRepository $postRepository,
        TagRepository $tagRepository
    ) {
        $this->renderer = $renderer;
        $this->postRepository = $postRepository;
        $this->tagRepository = $tagRepository;
    }

    /**
     * List all Posts
     *
     * @param Request $request
     * @param Response $response
     * @param int $page
     * @return Response
     */
    public function index(Request $request, Response $response, int $page = 1): Response
    {
        $limit = 8;
        $offset = $limit * ($page - 1);

        $posts = $this->postRepository->findAllOrderedByDateCreated($limit, $offset);

        $total = $this->postRepository->count();

        return $this->renderer->render($response, 'post-list', [
            'posts' => $posts,
            'current_page' => $page,
            'total_pages' => max(ceil($total / $limit), 1),
        ]);
    }

    /**
     * Find all posts tagged with the given tag's slug
     *
     * @param Request $request
     * @param Response $response
     * @param string $slug
     * @param int $page
     * @return Response
     */
    public function tagged(Request $request, Response $response, string $slug, int $page = 1): Response
    {
        $tag = $this->tagRepository->findBySlug($slug);

        if ($tag === null) {
            return $response->withStatus(404);
        }

        $limit = 8;
        $offset = $limit * ($page - 1);

        $posts = $this->postRepository->findAllTagged($tag->getSlug(), $limit, $offset);

        $total = $this->postRepository->countTagged($tag->getSlug());

        return $this->renderer->render($response, 'post-list', [
            'posts' => $posts,
            'current_page' => $page,
            'total_pages' => max(ceil($total / $limit), 1),
            'tag' => $tag,
        ]);
    }
}
