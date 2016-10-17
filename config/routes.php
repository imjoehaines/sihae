<?php

use Slim\App;
use Sihae\Middleware\AuthMiddleware;
use Sihae\Controllers\PostController;
use Sihae\Controllers\PageController;
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

    $app->group('/page', function () {
        $this->get('/new', PageController::class . ':create');
        $this->post('/new', PageController::class . ':store');
        $this->get('/edit/{slug:[a-zA-Z\d\s-_\-]+}', PageController::class . ':edit');
        $this->post('/edit/{slug:[a-zA-Z\d\s-_\-]+}', PageController::class . ':update');
        $this->get('/delete/{slug:[a-zA-Z\d\s-_\-]+}', PageController::class . ':delete');
    })->add(AuthMiddleware::class);

    $app->get('/page/{slug:[a-zA-Z\d\s-_\-]+}', PageController::class . ':show');

    $app->get('/login', LoginController::class . ':showForm');
    $app->post('/login', LoginController::class . ':login');
    $app->get('/logout', LoginController::class . ':logout');

    // $app->get('/register', RegistrationController::class . ':showForm');
    // $app->post('/register', RegistrationController::class . ':register');
};
