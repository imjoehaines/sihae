<?php

return [
    'settings' => [
        'sihae' => [
            'title' => 'Sihae',
            'summary' => 'Welcome to your Sihae blog',
            'show_login_link' => true,
        ],

        'database' => [
            'dsn' => 'sqlite:' . __DIR__ . '/../database.sq3',
            'username' => null,
            'password' => null,
            'attributes' => [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],
        ],

        // markdown settings
        'markdown' => [
            'enable_emphasis' => true,
            'enable_strong' => true,
            'use_asterisk' => true,
            'use_underscore' => true,
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
        ],

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
    ],
];
