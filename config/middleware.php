<?php

use Sihae\Middleware\SettingsProvider;
use Sihae\Middleware\NotFoundMiddleware;

return [
    NotFoundMiddleware::class, // 404 Handler
    SettingsProvider::class, // provide $settings to all views
];
