<?php

use Slim\App;
use Sihae\Middleware\AuthMiddleware;

return function (App $app) {
    $app->get('/[page/{page:[1-9][0-9]*}]', 'Sihae\PostController:index');

    $app->group('/', function () {
        $this->get('new', 'Sihae\PostController:create');
        $this->post('new', 'Sihae\PostController:store');
        $this->get('edit/{slug:[a-zA-Z\d\s-_\-]+}', 'Sihae\PostController:edit');
        $this->post('edit/{slug:[a-zA-Z\d\s-_\-]+}', 'Sihae\PostController:update');
        $this->get('delete/{slug:[a-zA-Z\d\s-_\-]+}', 'Sihae\PostController:delete');
    })->add(AuthMiddleware::class);

    $app->get('/archive', 'Sihae\ArchiveController:index');

    $app->get('/post/{slug:[a-zA-Z\d\s-_\-]+}', 'Sihae\PostController:show');

    $app->get('/login', 'Sihae\LoginController:showForm');
    $app->post('/login', 'Sihae\LoginController:login');
    $app->get('/logout', 'Sihae\LoginController:logout');

    $app->get('/register', 'Sihae\RegistrationController:showForm');
    $app->post('/register', 'Sihae\RegistrationController:register');
};
