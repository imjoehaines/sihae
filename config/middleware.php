<?php

use Slim\Csrf\Guard;
use Sihae\Middleware\CsrfProvider;
use Sihae\Middleware\SessionProvider;
use Sihae\Middleware\SettingsProvider;
use Sihae\Middleware\NotFoundMiddleware;
use Sihae\Middleware\FlashMessageProvider;

return [
    NotFoundMiddleware::class,
    SettingsProvider::class,
    FlashMessageProvider::class,
    SessionProvider::class,
    CsrfProvider::class,
    Guard::class,
];
