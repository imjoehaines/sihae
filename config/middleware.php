<?php

use Sihae\Middleware\SessionProvider;
use Sihae\Middleware\SettingsProvider;
use Sihae\Middleware\NotFoundMiddleware;
use Sihae\Middleware\FlashMessageProvider;

return [
    NotFoundMiddleware::class,
    SettingsProvider::class,
    FlashMessageProvider::class,
    SessionProvider::class,
];
