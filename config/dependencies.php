<?php

use RKA\Session;
use Monolog\Logger;
use Slim\Csrf\Guard;
use Slim\Http\Response;
use Slim\Flash\Messages;
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
use Sihae\LoginController;
use Sihae\ArchiveController;
use Sihae\RegistrationController;
use Sihae\Middleware\CsrfProvider;
use Sihae\Middleware\UserProvider;
use Sihae\Validators\PostValidator;
use Sihae\Middleware\AuthMiddleware;
use Sihae\Formatters\ArchiveFormatter;
use Sihae\Middleware\SettingsProvider;
use Sihae\Middleware\NotFoundMiddleware;
use Sihae\Middleware\FlashMessageProvider;
use Sihae\Validators\RegistrationValidator;

return function (Container $container) {
    $container[PostController::class] = function (Container $container) : PostController {
        return new PostController(
            $container->get('renderer'),
            $container->get(EntityManager::class),
            $container->get(CommonMarkConverter::class),
            $container->get(Messages::class),
            $container->get(PostValidator::class)
        );
    };

    $container[ArchiveController::class] = function (Container $container) : ArchiveController {
        return new ArchiveController(
            $container->get('renderer'),
            $container->get(EntityManager::class),
            $container->get(ArchiveFormatter::class)
        );
    };

    $container[LoginController::class] = function (Container $container) : LoginController {
        return new LoginController(
            $container->get('renderer'),
            $container->get(EntityManager::class),
            $container->get(Messages::class),
            $container->get(Session::class)
        );
    };

    $container[RegistrationController::class] = function (Container $container) : RegistrationController {
        return new RegistrationController(
            $container->get('renderer'),
            $container->get(RegistrationValidator::class),
            $container->get(EntityManager::class),
            $container->get(Messages::class),
            $container->get(Session::class)
        );
    };

    $container[ArchiveFormatter::class] = function (Container $container) : ArchiveFormatter {
        return new ArchiveFormatter();
    };

    $container[RegistrationValidator::class] = function (Container $container) : RegistrationValidator {
        return new RegistrationValidator();
    };

    $container[PostValidator::class] = function (Container $container) : PostValidator {
        return new PostValidator();
    };

    $container[AuthMiddleware::class] = function (Container $container) : AuthMiddleware {
        return new AuthMiddleware($container->get(Session::class));
    };

    $container[NotFoundMiddleware::class] = function (Container $container) : NotFoundMiddleware {
        return new NotFoundMiddleware($container->get('notFoundHandler'));
    };

    $container[SettingsProvider::class] = function (Container $container) : SettingsProvider {
        return new SettingsProvider(
            $container->get('renderer'),
            $container->get('settings')['sihae']
        );
    };

    $container[CsrfProvider::class] = function (Container $container) : CsrfProvider {
        return new CsrfProvider(
            $container->get('renderer'),
            $container->get(Guard::class)
        );
    };

    $container[Guard::class] = function (Container $container) : Guard {
        return new Guard();
    };

    $container[UserProvider::class] = function (Container $container) : UserProvider {
        return new UserProvider(
            $container->get('renderer'),
            $container->get(Session::class),
            $container->get(EntityManager::class)
        );
    };

    $container[FlashMessageProvider::class] = function (Container $container) : FlashMessageProvider {
        return new FlashMessageProvider(
            $container->get('renderer'),
            $container->get(Messages::class)
        );
    };

    $container[Session::class] = function (Container $container) : Session {
        return new Session;
    };

    $container[Messages::class] = function (Container $container) : Messages {
        return new Messages;
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
};
