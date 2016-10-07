<?php

namespace Sihae\Controllers;

use Sihae\Entities\Post;
use Slim\Flash\Messages;
use Slim\Views\PhpRenderer;
use Sihae\Validators\Validator;
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
     * @var Messages
     */
    private $flash;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @param PhpRenderer $renderer
     * @param EntityManager $entityManager
     * @param CommonMarkConverter $markdown
     * @param Messages $flash
     * @param Session $session
     * @param Validator $validator
     */
    public function __construct(
        PhpRenderer $renderer,
        EntityManager $entityManager,
        CommonMarkConverter $markdown,
        Messages $flash,
        Validator $validator
    ) {
        $this->renderer = $renderer;
        $this->entityManager = $entityManager;
        $this->markdown = $markdown;
        $this->flash = $flash;
        $this->validator = $validator;
    }

    /**
     * List all Posts
     *
     * @param Request $request
     * @param Response $response
     * @param integer $page
     * @return Response
     */
    public function index(Request $request, Response $response, int $page = 1) : Response
    {
        $postRepository = $this->entityManager->getRepository(Post::class);

        $limit = 4;
        $offset = $limit * ($page - 1);

        $posts = $postRepository->findBy([], ['date_created' => 'DESC'], $limit, $offset);

        $parsedPosts = array_map(function (Post $post) : Post {
            $parsedBody = $this->markdown->convertToHtml($post->getBody());

            return $post->setBody($parsedBody);
        }, $posts);

        $total = $postRepository->createQueryBuilder('Post')
            ->select('COUNT(Post.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return $this->renderer->render($response, 'layout.phtml', [
            'page' => 'post-list',
            'posts' => $parsedPosts,
            'current_page' => $page,
            'total_pages' => intdiv($total + $limit - 1, $limit),
        ]);
    }

    /**
     * Show form for creating a new Post
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
     * TODO: prevent duplicate slugs
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function store(Request $request, Response $response) : Response
    {
        $newPost = $request->getParsedBody();

        $post = new Post();
        $post->setTitle($newPost['title']);
        $post->setBody($newPost['body']);

        if (!$this->validator->isValid($newPost)) {
            return $this->renderer->render($response, 'layout.phtml', [
                'page' => 'post-form',
                'post' => $post,
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        $this->flash->addMessage('success', 'Successfully created your new post!');

        return $response->withStatus(302)->withHeader('Location', '/post/' . $post->getSlug());
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

        $post->setTitle($updatedPost['title']);
        $post->setBody($updatedPost['body']);

        if (!$this->validator->isValid($updatedPost)) {
            return $this->renderer->render($response, 'layout.phtml', [
                'page' => 'post-form',
                'post' => $post,
                'errors' => $this->validator->getErrors(),
                'isEdit' => true,
            ]);
        }

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        $this->flash->addMessage('success', 'Successfully edited your post!');

        return $response->withStatus(302)->withHeader('Location', '/post/' . $slug);
    }

    /**
     * Delete a Post
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

        $this->flash->addMessage('success', 'Successfully deleted your post!');

        return $response->withStatus(302)->withHeader('Location', '/');
    }
}
