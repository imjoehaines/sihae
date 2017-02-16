<?php declare(strict_types=1);

use Slim\Csrf\Guard;
use Sihae\Middleware\CsrfProvider;
use Sihae\Middleware\PageProvider;
use Sihae\Middleware\UserProvider;
use Sihae\Middleware\SettingsProvider;
use Sihae\Middleware\NotFoundMiddleware;

return [
    NotFoundMiddleware::class,
    SettingsProvider::class,
    UserProvider::class,
    PageProvider::class,
    CsrfProvider::class,
    Guard::class,
];
