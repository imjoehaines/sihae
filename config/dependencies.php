<?php

use Monolog\Logger;
use Slim\Http\Response;
use Slim\Views\PhpRenderer;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\UidProcessor;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use League\CommonMark\CommonMarkConverter;
use Slim\Interfaces\InvocationStrategyInterface;
use Slim\Handlers\Strategies\RequestResponseArgs;
use Interop\Container\ContainerInterface as Container;

use Sihae\PostController;

$container = $app->getContainer();

$container[PostController::class] = function (Container $container) : PostController {
    return new PostController(
        $container->get('renderer'),
        $container->get(EntityManager::class),
        $container->get(CommonMarkConverter::class)
    );
};

$container[CommonMarkConverter::class] = function (Container $container) : CommonMarkConverter {
    $settings = $container->get('settings')['markdown'];

    return new CommonMarkConverter($settings);
};

$container[EntityManager::class] = function (Container $container) : EntityManager {
    $settings = $container->get('settings')['doctrine'];

    $config = Setup::createAnnotationMetadataConfiguration(
        $settings['entity_path'],
        $settings['auto_generate_proxies'],
        $settings['proxy_dir'],
        $settings['cache'],
        false
    );

    return EntityManager::create($settings['connection'], $config);
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

// 404 handler
$container['notFoundHandler'] = function (Container $container) : callable {
    return function (RequestInterface $request, ResponseInterface $response) use ($container) {
        return $container->get('renderer')->render($response, 'layout.phtml', ['page' => '404']);
    };
};
