<?php

$isProduction = getenv('APP_ENV') === 'production';

return [
    'settings' => [
        'displayErrorDetails' => !$isProduction,
        'addContentLengthHeader' => false,

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // TODO make this confgurable
        'database' => [
            'dsn' => 'sqlite:' . __DIR__ . '/../database.sq3',
            'username' => null,
            'password' => null,
        ],
    ],
];
