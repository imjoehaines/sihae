<?php

use Slim\App;
use Sihae\Middleware\AuthMiddleware;
use League\CommonMark\CommonMarkConverter;

return function (App $app) {
    $app->get('/[page/{page:[1-9][0-9]*}]', 'Sihae\Controllers\PostController:index');

    $app->group('/post', function () {
        $this->get('/new', 'Sihae\Controllers\PostController:create');
        $this->post('/new', 'Sihae\Controllers\PostController:store');
        $this->get('/edit/{slug:[a-zA-Z\d\s-_\-]+}', 'Sihae\Controllers\PostController:edit');
        $this->post('/edit/{slug:[a-zA-Z\d\s-_\-]+}', 'Sihae\Controllers\PostController:update');
        $this->get('/delete/{slug:[a-zA-Z\d\s-_\-]+}', 'Sihae\Controllers\PostController:delete');
    })->add(AuthMiddleware::class);

    $app->get('/post/{slug:[a-zA-Z\d\s-_\-]+}', 'Sihae\Controllers\PostController:show');

    $app->get('/archive', 'Sihae\Controllers\ArchiveController:index');

    $app->get('/static/{page}', function ($request, $response, $page) {
        $renderer = $this->get('Sihae\Renderer');
        $path = __DIR__ . '/../data/static/' . $page . '.md';

        if (!file_exists($path)) {
            return $response->withStatus(404);
        }

        $rawContent = file_get_contents($path);

        $markdown = $this->get(CommonMarkConverter::class);
        $content = $markdown->convertToHtml($rawContent);

        return $renderer->render($response, 'static', ['content' => $content]);
    });

    $app->get('/login', 'Sihae\Controllers\LoginController:showForm');
    $app->post('/login', 'Sihae\Controllers\LoginController:login');
    $app->get('/logout', 'Sihae\Controllers\LoginController:logout');

    // $app->get('/register', 'Sihae\Controllers\RegistrationController:showForm');
    // $app->post('/register', 'Sihae\Controllers\RegistrationController:register');
};
