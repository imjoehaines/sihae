<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Sihae\Container;
use Sihae\Middleware\ErrorMiddleware;
use Sihae\SlimRequestInvocationStrategy;
use Slim\App;
use Tracy\Debugger;

if (PHP_SAPI === 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];

    if (is_file($file)) {
        return false;
    }
}

// load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// enable debug bar if we're not in production
if (getenv('APPLICATION_ENV') !== 'production') {
    Debugger::enable();
}

session_start([
    'name' => 'Sihae',
    'use_strict_mode' => true,
    'use_only_cookies' => true,
    'gc_maxlifetime' => 60 * 15,
    'cookie_lifetime' => 0,
    'cookie_httponly' => true,
    'sid_length' => 64,
    'sid_bits_per_character' => 6,
]);

// Instantiate the app
$settings = require __DIR__ . '/../config/settings.php';

$container = new Container($settings);
$psr17Factory = new Psr17Factory();
$creator = new ServerRequestCreator($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);

$request = $creator->fromGlobals();
// TODO See if we can remove the request from the container
$container['request'] = $request;

$app = new App($psr17Factory, $container);

$routeCollector = $app->getRouteCollector();
$routeCollector->setDefaultInvocationStrategy(new SlimRequestInvocationStrategy());

// Set up dependencies
$dependencyFactory = require __DIR__ . '/../config/dependencies.php';
$dependencyFactory($container);

// Register middleware
$middlewares = require __DIR__ . '/../config/middleware.php';

foreach ($middlewares as $middleware) {
    $app->add($middleware);
}

// Register routes
$routeFactory = require __DIR__ . '/../config/routes.php';
$routeFactory($app);

// convert all warnings, notices etc... into ErrorExceptions
set_error_handler(function ($severity, $message, $file, $line): void {
    throw new ErrorException($message, 0, $severity, $file, $line);
}, E_ALL);

$app->addRoutingMiddleware();

if ($container->has(ErrorMiddleware::class)) {
    $app->addMiddleware($container->get(ErrorMiddleware::class));
}

$app->run($request);
