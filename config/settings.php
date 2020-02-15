<?php

declare(strict_types=1);

use Monolog\Logger;

$isProduction = getenv('APPLICATION_ENV') === 'production';

return [
    'settings' => [
        'sihae' => [
            'title' => getenv('SIHAE_TITLE') ?: 'Sihae',
            'summary' => getenv('SIHAE_SUMMARY') ?: 'Welcome to your Sihae blog',
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
            'proxy_dir' => __DIR__ . '/../data/cache/proxies',
            'cache' => null,
            'connection' => [
                'driver' => getenv('DB_DRIVER') ?: 'pdo_mysql',
                'path' => getenv('DB_PATH') ?: __DIR__ . '/../data/database.sq3',
                'host' => getenv('DB_HOST') ?: 'localhost',
                'port' => getenv('DB_PORT') ?: 3306,
                'dbname' => getenv('DB_NAME') ?: 'sihae',
                'user' => getenv('DB_USER') ?: 'root',
                'password' => getenv('DB_PASSWORD') ?: '',
            ],
        ],

        'routerCacheFile' => $isProduction ? __DIR__ . '/../data/cache/router.php' : false,

        // Renderer settings
        'renderer' => [
            'path' => __DIR__ . '/../templates/',
            'extension' => 'phtml',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'sihae',
            'path' => __DIR__ . '/../data/logs/app.log',
            'level' => Logger::DEBUG,
        ],
    ],
];
