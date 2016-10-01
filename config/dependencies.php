<?php

use Monolog\Logger;
use Slim\Views\PhpRenderer;
use Psr\Log\LoggerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\UidProcessor;
use Slim\Interfaces\InvocationStrategyInterface;
use Slim\Handlers\Strategies\RequestResponseArgs;
use Interop\Container\ContainerInterface as Container;

use Sihae\PostRepository;

$container = $app->getContainer();

$container[PostRepository::class] = function (Container $container) : PostRepository {
    return new PostRepository($container->get('database'));
};

$container['database'] = function (Container $container) : PDO {
    $settings = $container->get('settings')['database'];

    return new PDO($settings['dsn'], $settings['username'], $settings['password'], $settings['attributes']);
};

// view renderer
$container['renderer'] = function (Container $container) : PhpRenderer {
    $settings = $container->get('settings')['renderer'];

    return new PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function (Container $container) : LoggerInterface {
    $settings = $container->get('settings')['logger'];

    $logger = new Logger($settings['name']);
    $logger->pushProcessor(new UidProcessor());
    $logger->pushHandler(new StreamHandler($settings['path'], $settings['level']));

    return $logger;
};

$container['foundHandler'] = function (Container $container) : InvocationStrategyInterface {
    return new RequestResponseArgs();
};
