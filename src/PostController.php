<?php

namespace Sihae;

use Slim\Views\PhpRenderer;
use Doctrine\ORM\EntityManager;
use League\CommonMark\CommonMarkConverter;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class PostController
{
    private $renderer;
    private $entityManager;
    private $markdown;

    public function __construct(
        PhpRenderer $renderer,
        EntityManager $entityManager,
        CommonMarkConverter $markdown
    ) {
        $this->renderer = $renderer;
        $this->entityManager = $entityManager;
        $this->markdown = $markdown;
    }

    public function index(Request $request, Response $response) : Response
    {
        $posts = $this->entityManager->getRepository(Post::class)->findAll();

        $parsedPosts = array_map(function (Post $post) : Post {
            $parsedBody = $this->markdown->convertToHtml($post->getBody());

            return $post->setBody($parsedBody);
        }, $posts);

        return $this->renderer->render($response, 'layout.phtml', [
            'page' => 'post-list',
            'posts' => $parsedPosts,
        ]);
    }

    public function create(Request $request, Response $response) : Response
    {
        return $this->renderer->render($response, 'layout.phtml', ['page' => 'post-form']);
    }

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
}
