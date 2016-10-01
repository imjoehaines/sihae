<?php

use function Stringy\create as s;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/', function (Request $request, Response $response) : Response {
    $db = $this->get('database');

    $query = 'SELECT * FROM posts ORDER BY date_created DESC;';

    $statement = $db->prepare($query);
    $statement->execute();

    $posts = array_map(function (array $post) {
        $post['summary'] = s($post['body'])->safeTruncate(450, '…');

        return $post;
    }, $statement->fetchAll());

    return $this->get('renderer')->render($response, 'layout.phtml', [
        'page' => 'post-list',
        'posts' => $posts,
    ]);
});

$app->get('/post/new', function (Request $request, Response $response) : Response {
    return $this->get('renderer')->render($response, 'layout.phtml', ['page' => 'post-form']);
});

$app->post('/post/new', function (Request $request, Response $response) : Response {
    $post = $request->getParsedBody();

    if (isset($post['title'], $post['body'])) {
        $slug = s($post['title'])->slugify();

        $db = $this->get('database');
        $query = 'INSERT INTO posts (title, slug, body) VALUES (:title, :slug, :body);';

        $statement = $db->prepare($query);
        $statement->execute(['title' => $post['title'], 'slug' => $slug, 'body' => $post['body']]);

        return $response->withStatus(302)->withHeader('Location', '/');
    }

    return $this->get('renderer')->render($response, 'layout.phtml', [
        'page' => 'post-form',
        'post' => $post,
    ]);
});

$app->get('/post/edit/{slug}', function (Request $request, Response $response, string $slug) : Response {
    $db = $this->get('database');

    $query = 'SELECT * FROM posts WHERE slug = :slug;';

    $statement = $db->prepare($query);
    $statement->execute(['slug' => $slug]);

    $post = $statement->fetch();

    if (empty($post)) {
        return $response->withStatus(404);
    }

    return $this->get('renderer')->render($response, 'layout.phtml', [
        'page' => 'post-form',
        'post' => $post,
        'isEdit' => true,
    ]);
});

$app->post('/post/edit/{slug}', function (Request $request, Response $response, string $slug) : Response {
    $db = $this->get('database');

    $query = 'SELECT * FROM posts WHERE slug = :slug;';

    $statement = $db->prepare($query);
    $statement->execute(['slug' => $slug]);

    $post = $statement->fetch();

    if (empty($post)) {
        return $response->withStatus(404);
    }

    $updatedPost = $request->getParsedBody();

    if (isset($updatedPost['title'], $updatedPost['body'])) {
        $query ='UPDATE posts SET title = :title, body = :body WHERE slug = :slug;';

        $statement = $db->prepare($query);
        $statement->execute([
            'title' => $updatedPost['title'],
            'body' => $updatedPost['body'],
            'slug' => $slug,
        ]);

        return $response->withStatus(302)->withHeader('Location', '/');
    }

    return $this->get('renderer')->render($response, 'layout.phtml', [
        'page' => 'post-form',
        'post' => $updatedPost,
        'isEdit' => true,
    ]);
});

$app->get('/post/{slug}', function (Request $request, Response $response, string $slug) : Response {
    $db = $this->get('database');

    $query = 'SELECT * FROM posts WHERE slug = :slug;';

    $statement = $db->prepare($query);
    $statement->execute(['slug' => $slug]);

    $post = $statement->fetch();

    if (empty($post)) {
        return $response->withStatus(404);
    }

    return $this->get('renderer')->render($response, 'layout.phtml', [
        'page' => 'post',
        'post' => $post,
    ]);
});
