<?php

namespace Sihae\Controllers;

use Sihae\Renderer;
use Sihae\Entities\Tag;
use Sihae\Entities\Post;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

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
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param Renderer $renderer
     * @param EntityManager $entityManager
     */
    public function __construct(
        Renderer $renderer,
        EntityManager $entityManager
    ) {
        $this->renderer = $renderer;
        $this->entityManager = $entityManager;
    }

    /**
     * List all Posts
     *
     * @param Request $request
     * @param Response $response
     * @param int $page
     * @return Response
     */
    public function index(Request $request, Response $response, int $page = 1) : Response
    {
        $postRepository = $this->entityManager->getRepository(Post::class);

        $limit = 8;
        $offset = $limit * ($page - 1);

        $posts = $postRepository->findBy(['is_page' => false], ['date_created' => 'DESC'], $limit, $offset);

        $total = $postRepository->createQueryBuilder('Post')
            ->select('COUNT(Post.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return $this->renderer->render($response, 'post-list', [
            'posts' => $posts,
            'current_page' => $page,
            'total_pages' => ceil($total / $limit) ?: 1,
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
    public function tagged(Request $request, Response $response, string $slug, int $page = 1) : Response
    {
        $tag = $this->entityManager->getRepository(Tag::class)->findOneBy(['slug' => $slug]);

        if (!$tag) {
            return $response->withStatus(404);
        }

        $limit = 8;
        $offset = $limit * ($page - 1);

        $dql =
            'SELECT p, t
             FROM Sihae\Entities\Post p
             JOIN p.tags t
             WHERE t.slug = :slug
             ORDER BY p.date_created DESC';

        $query = $this->entityManager->createQuery($dql)
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit)
            ->setParameter(':slug', $slug);

        $posts = $query->getResult();

        $total = $this->entityManager->getRepository(Post::class)
            ->createQueryBuilder('Post')
            ->select('COUNT(Post.id)')
            ->join('Post.tags', 't')
            ->where('t.slug = :slug')
            ->setParameter(':slug', $slug)
            ->getQuery()
            ->getSingleScalarResult();

        return $this->renderer->render($response, 'post-list', [
            'posts' => $posts,
            'current_page' => $page,
            'total_pages' => ceil($total / $limit) ?: 1,
            'tag' => $tag,
        ]);
    }
}
