<?php

use Slim\App;
use Sihae\Middleware\PostLocator;
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

        $this->group('', function () {
            $this->get('/edit/{slug:[a-zA-Z\d\s-_\-]+}', PostController::class . ':edit');
            $this->post('/edit/{slug:[a-zA-Z\d\s-_\-]+}', PostController::class . ':update');
            $this->get('/delete/{slug:[a-zA-Z\d\s-_\-]+}', PostController::class . ':delete');
            $this->get('/convert/{slug:[a-zA-Z\d\s-_\-]+}', PostController::class . ':convert');
        })->add(PostLocator::class);
    })->add(AuthMiddleware::class);

    $app->get('/post/{slug:[a-zA-Z\d\s-_\-]+}', PostController::class . ':show')
        ->add(PostLocator::class);

    $app->get('/tagged/{slug:[a-zA-Z\d\s-_\-]+}[/page/{page:[1-9][0-9]*}]', PostController::class . ':tagged');

    $app->get('/archive', ArchiveController::class . ':index');

    $app->get('/login', LoginController::class . ':showForm');
    $app->post('/login', LoginController::class . ':login');
    $app->get('/logout', LoginController::class . ':logout');

    $app->get('/{slug:[a-zA-Z\d\s-_\-]+}', PostController::class . ':show')
        ->add(PostLocator::class);

    // $app->get('/register', RegistrationController::class . ':showForm');
    // $app->post('/register', RegistrationController::class . ':register');
};
