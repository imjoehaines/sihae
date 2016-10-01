<?php

return [
    'settings' => [
        'displayErrorDetails' => true,
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
            'attributes' => [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],
        ],
    ],
];
