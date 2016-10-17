<?php

use Slim\Csrf\Guard;
use Sihae\Middleware\CsrfProvider;
use Sihae\Middleware\UserProvider;
use Sihae\Middleware\SettingsProvider;
use Sihae\Middleware\NotFoundMiddleware;
use Sihae\Middleware\StaticPageProvider;
use Sihae\Middleware\FlashMessageProvider;

return [
    NotFoundMiddleware::class,
    SettingsProvider::class,
    FlashMessageProvider::class,
    UserProvider::class,
    StaticPageProvider::class,
    CsrfProvider::class,
    Guard::class,
];
