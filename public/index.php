<?php

set_error_handler(function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
}, E_ALL);

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];

    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

session_start();

// Instantiate the app
$settings = require __DIR__ . '/../config/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
$dependencyFactory = require __DIR__ . '/../config/dependencies.php';
$dependencyFactory($app->getContainer());

// Register middleware
$middleware = require __DIR__ . '/../config/middleware.php';
array_map([$app, 'add'], $middleware);

// Register routes
$routeFactory = require __DIR__ . '/../config/routes.php';
$routeFactory($app);

// Run app
$app->run();
