<?php

use Sihae\Post;
use Sihae\PostRepository;
use Sihae\PostNotFoundException;
use function Stringy\create as s;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/', function (Request $request, Response $response) : Response {
    $postRepository = $this->get(PostRepository::class);
    $posts = $postRepository->findAll();

    return $this->get('renderer')->render($response, 'layout.phtml', [
        'page' => 'post-list',
        'posts' => $posts,
    ]);
});

$app->get('/post/new', function (Request $request, Response $response) : Response {
    return $this->get('renderer')->render($response, 'layout.phtml', ['page' => 'post-form']);
});

$app->post('/post/new', function (Request $request, Response $response) : Response {
    $newPost = $request->getParsedBody();

    if (isset($newPost['title'], $newPost['body'])) {
        $db = $this->get('database');
        $post = new Post($db, ['title' => $newPost['title'], 'body' => $newPost['body']]);
        $post->slug = s($post->title)->slugify();

        $post->save();

        return $response->withStatus(302)->withHeader('Location', '/post/' . $post->slug);
    }

    return $this->get('renderer')->render($response, 'layout.phtml', [
        'page' => 'post-form',
        'post' => $newPost,
    ]);
});

$app->get('/post/edit/{slug}', function (Request $request, Response $response, string $slug) : Response {
    $postRepository = $this->get(PostRepository::class);

    try {
        $post = $postRepository->findBySlug($slug);
    } catch (PostNotFoundException $e) {
        return $response->withStatus(404);
    }

    return $this->get('renderer')->render($response, 'layout.phtml', [
        'page' => 'post-form',
        'post' => $post,
        'isEdit' => true,
    ]);
});

$app->post('/post/edit/{slug}', function (Request $request, Response $response, string $slug) : Response {
    $postRepository = $this->get(PostRepository::class);

    try {
        $post = $postRepository->findBySlug($slug);
    } catch (PostNotFoundException $e) {
        return $response->withStatus(404);
    }

    $updatedPost = $request->getParsedBody();

    if (isset($updatedPost['title'], $updatedPost['body'])) {
        $post->title = $updatedPost['title'];
        $post->body = $updatedPost['body'];

        $post->save();

        return $response->withStatus(302)->withHeader('Location', '/post/' . $slug);
    }

    return $this->get('renderer')->render($response, 'layout.phtml', [
        'page' => 'post-form',
        'post' => $updatedPost,
        'isEdit' => true,
    ]);
});

$app->get('/post/{slug}', function (Request $request, Response $response, string $slug) : Response {
    $postRepository = $this->get(PostRepository::class);

    try {
        $post = $postRepository->findBySlug($slug);
    } catch (PostNotFoundException $e) {
        return $response->withStatus(404);
    }

    return $this->get('renderer')->render($response, 'layout.phtml', [
        'page' => 'post',
        'post' => $post,
    ]);
});
