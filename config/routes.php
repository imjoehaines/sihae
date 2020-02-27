<?php

declare(strict_types=1);

use Sihae\Actions\ArchiveAction;
use Sihae\Actions\LoginAction;
use Sihae\Actions\LoginFormAction;
use Sihae\Actions\LogoutAction;
use Sihae\Controllers\PostController;
use Sihae\Controllers\PostListController;
use Sihae\Controllers\RegistrationController;
use Sihae\Controllers\TagController;
use Sihae\Middleware\AuthMiddleware;
use Sihae\Middleware\PostLocator;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return static function (App $app): void {
    $app->get('/[page/{page:[1-9][0-9]*}]', PostListController::class . ':index');

    $app->group('/post', function (RouteCollectorProxy $group): void {
        $group->get('/new', PostController::class . ':create');
        $group->post('/new', PostController::class . ':store');

        $group->group('', function (RouteCollectorProxy $group): void {
            $group->get('/edit/{slug:[a-zA-Z\d\s\-_\-]+}', PostController::class . ':edit');
            $group->post('/edit/{slug:[a-zA-Z\d\s\-_\-]+}', PostController::class . ':update');
            $group->get('/delete/{slug:[a-zA-Z\d\s\-_\-]+}', PostController::class . ':delete');
            $group->get('/convert/{slug:[a-zA-Z\d\s\-_\-]+}', PostController::class . ':convert');
        })->add(PostLocator::class);
    })->add(AuthMiddleware::class);

    $app->get('/post/{slug:[a-zA-Z\d\s\-_\-]+}', PostController::class . ':show')
        ->add(PostLocator::class);

    $app->get('/tagged/{slug:[a-zA-Z\d\s\-_\-]+}[/page/{page:[1-9][0-9]*}]', PostListController::class . ':tagged');

    $app->get('/archive', ArchiveAction::class);
    $app->get('/tags', TagController::class . ':index');

    $app->get('/login', LoginFormAction::class);
    $app->post('/login', LoginAction::class);
    $app->get('/logout', LogoutAction::class);

    if ((bool) getenv('ENABLE_REGISTRATION') === true) {
        $app->get('/register', RegistrationController::class . ':showForm');
        $app->post('/register', RegistrationController::class . ':register');
    }

    $app->get('/{slug:[a-zA-Z\d\s\-_\-]+}', PostController::class . ':show')
        ->add(PostLocator::class);
};
