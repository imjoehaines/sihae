<?php

declare(strict_types=1);

use Sihae\Middleware\CsrfProvider;
use Sihae\Middleware\NotFoundMiddleware;
use Sihae\Middleware\SettingsProvider;
use Sihae\Middleware\UserProvider;
use Slim\Csrf\Guard;

return [
    NotFoundMiddleware::class,
    SettingsProvider::class,
    UserProvider::class,
    CsrfProvider::class,
    Guard::class,
];
