<?php

declare(strict_types=1);

use Sihae\Actions\ArchivedPostsAction;
use Sihae\Actions\CreatePostAction;
use Sihae\Actions\LoginAction;
use Sihae\Actions\LoginFormAction;
use Sihae\Actions\LogoutAction;
use Sihae\Actions\PostFormAction;
use Sihae\Actions\PostListAction;
use Sihae\Actions\PostListTaggedAction;
use Sihae\Actions\RegisterUserAction;
use Sihae\Actions\RegistrationFormAction;
use Sihae\Actions\TagListAction;
use Sihae\Controllers\PostController;
use Sihae\Middleware\AuthMiddleware;
use Sihae\Middleware\PostLocator;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return static function (App $app): void {
    $app->get('/[page/{page:[1-9][0-9]*}]', PostListAction::class);

    $app->group('/post', function (RouteCollectorProxy $group): void {
        $group->get('/new', PostFormAction::class);
        $group->post('/new', CreatePostAction::class);

        $group->group('', function (RouteCollectorProxy $group): void {
            $group->get('/edit/{slug:[a-zA-Z\d\s\-_\-]+}', PostController::class . ':edit');
            $group->post('/edit/{slug:[a-zA-Z\d\s\-_\-]+}', PostController::class . ':update');
            $group->get('/delete/{slug:[a-zA-Z\d\s\-_\-]+}', PostController::class . ':delete');
            $group->get('/convert/{slug:[a-zA-Z\d\s\-_\-]+}', PostController::class . ':convert');
        })->add(PostLocator::class);
    })->add(AuthMiddleware::class);

    $app->get('/post/{slug:[a-zA-Z\d\s\-_\-]+}', PostController::class . ':show')
        ->add(PostLocator::class);

    $app->get('/tagged/{slug:[a-zA-Z\d\s\-_\-]+}[/page/{page:[1-9][0-9]*}]', PostListTaggedAction::class);

    $app->get('/archive', ArchivedPostsAction::class);
    $app->get('/tags', TagListAction::class);

    $app->get('/login', LoginFormAction::class);
    $app->post('/login', LoginAction::class);
    $app->get('/logout', LogoutAction::class);

    if ((bool) getenv('ENABLE_REGISTRATION') === true) {
        $app->get('/register', RegistrationFormAction::class);
        $app->post('/register', RegisterUserAction::class);
    }

    $app->get('/{slug:[a-zA-Z\d\s\-_\-]+}', PostController::class . ':show')
        ->add(PostLocator::class);
};
