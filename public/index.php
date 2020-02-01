<?php declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Slim\App;
use Dotenv\Dotenv;
use Tracy\Debugger;
use Sihae\Container;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;

if (PHP_SAPI === 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
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

$container =  new Container($settings);
$psr17Factory = new Psr17Factory();
$creator = new ServerRequestCreator($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);

$request = $creator->fromGlobals();

$app = new App($psr17Factory, $container);

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
set_error_handler(function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
}, E_ALL);

$app->run();
