<?php

use Slim\App;
use Sihae\Middleware\AuthMiddleware;

return function (App $app) {
    $app->get('/[page/{page:[1-9][0-9]*}]', 'Sihae\Controllers\PostController:index');

    $app->group('/', function () {
        $this->get('new', 'Sihae\Controllers\PostController:create');
        $this->post('new', 'Sihae\Controllers\PostController:store');
        $this->get('edit/{slug:[a-zA-Z\d\s-_\-]+}', 'Sihae\Controllers\PostController:edit');
        $this->post('edit/{slug:[a-zA-Z\d\s-_\-]+}', 'Sihae\Controllers\PostController:update');
        $this->get('delete/{slug:[a-zA-Z\d\s-_\-]+}', 'Sihae\Controllers\PostController:delete');
    })->add(AuthMiddleware::class);

    $app->get('/archive', 'Sihae\Controllers\ArchiveController:index');
    $app->get('/about', function ($request, $response) {
        $renderer = $this->get('Sihae\Renderer');

        return $renderer->render($response, 'about');
    });

    $app->get('/post/{slug:[a-zA-Z\d\s-_\-]+}', 'Sihae\Controllers\PostController:show');

    $app->get('/login', 'Sihae\Controllers\LoginController:showForm');
    $app->post('/login', 'Sihae\Controllers\LoginController:login');
    $app->get('/logout', 'Sihae\Controllers\LoginController:logout');

    $app->get('/register', 'Sihae\Controllers\RegistrationController:showForm');
    $app->post('/register', 'Sihae\Controllers\RegistrationController:register');
};
