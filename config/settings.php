<?php

return [
    'settings' => [
        'sihae' => [
            'title' => 'Sihae',
            'summary' => 'Welcome to your Sihae blog',
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

        'doctrine' => [
            'entity_path' => ['src/'],
            'auto_generate_proxies' => true,
            'proxy_dir' =>  __DIR__ . '/../data/cache/proxies',
            'cache' => null,
            'connection' => [
                'driver' => 'pdo_sqlite',
                'path' => __DIR__ . '/../database.sq3',
                'host' => null,
                'dbname' => null,
                'user' => null,
                'password' => null,
            ],
        ],

        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'sihae',
            'path' => __DIR__ . '/../data/logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
    ],
];
