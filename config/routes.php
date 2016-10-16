<?php

use Slim\App;
use Sihae\Middleware\AuthMiddleware;
use Sihae\Controllers\PostController;
use Sihae\Controllers\LoginController;
use Sihae\Controllers\ArchiveController;
use League\CommonMark\CommonMarkConverter;
use Sihae\Controllers\RegistrationController;

return function (App $app) {
    $app->get('/[page/{page:[1-9][0-9]*}]', PostController::class . ':index');

    $app->group('/post', function () {
        $this->get('/new', PostController::class . ':create');
        $this->post('/new', PostController::class . ':store');
        $this->get('/edit/{slug:[a-zA-Z\d\s-_\-]+}', PostController::class . ':edit');
        $this->post('/edit/{slug:[a-zA-Z\d\s-_\-]+}', PostController::class . ':update');
        $this->get('/delete/{slug:[a-zA-Z\d\s-_\-]+}', PostController::class . ':delete');
    })->add(AuthMiddleware::class);

    $app->get('/post/{slug:[a-zA-Z\d\s-_\-]+}', PostController::class . ':show');

    $app->get('/archive', ArchiveController::class . ':index');

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

    $app->get('/login', LoginController::class . ':showForm');
    $app->post('/login', LoginController::class . ':login');
    $app->get('/logout', LoginController::class . ':logout');

    // $app->get('/register', RegistrationController::class . ':showForm');
    // $app->post('/register', RegistrationController::class . ':register');
};
