<?php

namespace Sihae;

use Slim\Views\PhpRenderer;
use Doctrine\ORM\EntityManager;
use League\CommonMark\CommonMarkConverter;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class PostController
{
    /**
     * @var PhpRenderer
     */
    private $renderer;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var CommonMarkConverter
     */
    private $markdown;

    /**
     * @param PhpRenderer $renderer
     * @param EntityManager $entityManager
     * @param CommonMarkConverter $markdown
     */
    public function __construct(
        PhpRenderer $renderer,
        EntityManager $entityManager,
        CommonMarkConverter $markdown
    ) {
        $this->renderer = $renderer;
        $this->entityManager = $entityManager;
        $this->markdown = $markdown;
    }

    /**
     * List all Posts
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function index(Request $request, Response $response, int $page = 1) : Response
    {
        $postRepository = $this->entityManager->getRepository(Post::class);

        $limit = 5;
        $offset = $limit * ($page - 1);

        $posts = $postRepository->findBy([], ['date_created' => 'DESC'], $limit, $offset);

        $parsedPosts = array_map(function (Post $post) : Post {
            $parsedBody = $this->markdown->convertToHtml($post->getBody());

            return $post->setBody($parsedBody);
        }, $posts);

        $total = $postRepository->createQueryBuilder('Sihae\Post')
            ->select('COUNT(Sihae\Post.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return $this->renderer->render($response, 'layout.phtml', [
            'page' => 'post-list',
            'posts' => $parsedPosts,
            'current_page' => $page,
            'total_pages' => intdiv($total + $limit - 1, $limit),
            'total' => $total,
        ]);
    }

    /**
     * Show form for creating a new Post
     *
     * TODO: authorisation
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function create(Request $request, Response $response) : Response
    {
        return $this->renderer->render($response, 'layout.phtml', ['page' => 'post-form']);
    }

    /**
     * Save a new Post
     *
     * TODO: validation
     * TODO: authorisation
     * TODO: prevent duplicate slugs
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function store(Request $request, Response $response) : Response
    {
        $newPost = $request->getParsedBody();

        if (isset($newPost['title'], $newPost['body'])) {
            $post = new Post();
            $post->setTitle($newPost['title']);
            $post->setBody($newPost['body']);

            $this->entityManager->persist($post);
            $this->entityManager->flush();

            return $response->withStatus(302)->withHeader('Location', '/post/' . $post->getSlug());
        }

        return $this->renderer->render($response, 'layout.phtml', [
            'page' => 'post-form',
            'post' => $newPost,
        ]);
    }

    /**
     * Show a single Post
     *
     * @param Request $request
     * @param Response $response
     * @param string $slug
     * @return Response
     */
    public function show(Request $request, Response $response, string $slug) : Response
    {
        $post = $this->entityManager->getRepository(Post::class)->findOneBy(['slug' => $slug]);

        if (!$post) {
            return $response->withStatus(404);
        }

        $parsedBody = $this->markdown->convertToHtml($post->getBody());
        $parsedPost = $post->setBody($parsedBody);

        return $this->renderer->render($response, 'layout.phtml', [
            'page' => 'post',
            'post' => $parsedPost,
        ]);
    }

    /**
     * Show the form to edit an existing Post
     *
     * TODO: authorisation
     *
     * @param Request $request
     * @param Response $response
     * @param string $slug
     * @return Response
     */
    public function edit(Request $request, Response $response, string $slug) : Response
    {
        $post = $this->entityManager->getRepository(Post::class)->findOneBy(['slug' => $slug]);

        if (!$post) {
            return $response->withStatus(404);
        }

        return $this->renderer->render($response, 'layout.phtml', [
            'page' => 'post-form',
            'post' => $post,
            'isEdit' => true,
        ]);
    }

    /**
     * Save updates to an existing Post
     *
     * TODO: validation
     * TODO: authorisation
     *
     * @param Request $request
     * @param Response $response
     * @param string $slug
     * @return Response
     */
    public function update(Request $request, Response $response, string $slug) : Response
    {
        $post = $this->entityManager->getRepository(Post::class)->findOneBy(['slug' => $slug]);

        if (!$post) {
            return $response->withStatus(404);
        }

        $updatedPost = $request->getParsedBody();

        if (isset($updatedPost['title'], $updatedPost['body'])) {
            $post->setTitle($updatedPost['title']);
            $post->setBody($updatedPost['body']);

            $this->entityManager->persist($post);
            $this->entityManager->flush();

            return $response->withStatus(302)->withHeader('Location', '/post/' . $slug);
        }

        return $this->renderer->render($response, 'layout.phtml', [
            'page' => 'post-form',
            'post' => $updatedPost,
            'isEdit' => true,
        ]);
    }

    /**
     * Delete a Post
     *
     * TODO: authorisation
     *
     * @param Request $request
     * @param Response $response
     * @param string $slug
     * @return Response
     */
    public function delete(Request $request, Response $response, string $slug) : Response
    {
        $post = $this->entityManager->getRepository(Post::class)->findOneBy(['slug' => $slug]);

        if (!$post) {
            return $response->withStatus(404);
        }

        $this->entityManager->remove($post);
        $this->entityManager->flush();

        return $response->withStatus(302)->withHeader('Location', '/');
    }
}
