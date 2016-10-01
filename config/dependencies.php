<?php

use Monolog\Logger;
use Slim\Views\PhpRenderer;
use Psr\Log\LoggerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\UidProcessor;
use Interop\Container\ContainerInterface as Container;

$container = $app->getContainer();

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
