<?php

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/', function (Request $request, Response $response) : Response {
    $db = $this->get('database');

    $query = 'SELECT * FROM posts;';

    $statement = $db->prepare($query);
    $statement->execute();

    return $this->get('renderer')->render($response, 'layout.phtml', [
        'page' => 'post-list',
        'posts' => $statement->fetchAll(),
    ]);
});

$app->get('/post/{slug}', function (Request $request, Response $response, $slug) : Response {
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
