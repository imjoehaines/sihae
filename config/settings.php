<?php

$dotenv = new Dotenv\Dotenv(__DIR__ . '/..');
$dotenv->load();

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
            'entity_path' => ['src/Entities'],
            'auto_generate_proxies' => true,
            'proxy_dir' =>  __DIR__ . '/../data/cache/proxies',
            'cache' => null,
            'connection' => [
                'driver' => getenv('DB_DRIVER') ?: 'pdo_mysql',
                'path' => getenv('DB_PATH') ?: __DIR__ . '/../data/database.sq3',
                'host' => getenv('DB_HOST') ?: 'localhost',
                'dbname' => getenv('DB_NAME') ?: 'sihae',
                'user' => getenv('DB_USER') ?: 'root',
                'password' => getenv('DB_PASSWORD') ?: '',
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
