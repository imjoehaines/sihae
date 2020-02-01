<?php declare(strict_types=1);

use RKA\Session;
use Monolog\Logger;
use Slim\Csrf\Guard;
use League\Plates\Engine;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use League\Plates\Extension\URI;
use League\Plates\Extension\Asset;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\UidProcessor;
use Monolog\Processor\WebProcessor;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\UriFactoryInterface;
use League\CommonMark\CommonMarkConverter;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;

use Sihae\Renderer;
use Sihae\Container;
use Sihae\Middleware\PostLocator;
use Sihae\Middleware\CsrfProvider;
use Sihae\Middleware\PageProvider;
use Sihae\Middleware\UserProvider;
use Sihae\Validators\PostValidator;
use Sihae\Middleware\AuthMiddleware;
use Sihae\Controllers\TagController;
use Sihae\Controllers\PostController;
use Sihae\Repositories\TagRepository;
use Sihae\Repositories\PostRepository;
use Sihae\Repositories\UserRepository;
use Sihae\Formatters\ArchiveFormatter;
use Sihae\Middleware\SettingsProvider;
use Sihae\Controllers\ErrorController;
use Sihae\Controllers\LoginController;
use Sihae\Controllers\ArchiveController;
use Sihae\Middleware\NotFoundMiddleware;
use Sihae\Controllers\PostListController;
use Sihae\Validators\RegistrationValidator;
use Sihae\Controllers\RegistrationController;
use Sihae\Repositories\Doctrine\DoctrineTagRepository;
use Sihae\Repositories\Doctrine\DoctrinePostRepository;
use Sihae\Repositories\Doctrine\DoctrineUserRepository;

return function (Container $container) {
    $container[Engine::class] = function (Container $container) : Engine {
        $settings = $container->get('settings')['renderer'];

        $engine = new Engine($settings['path'], $settings['extension']);
        $engine->loadExtension(new Asset(__DIR__ . '/../public/'));
        $engine->loadExtension(new URI($container->get('request')->getUri()->getPath()));
        $engine->addFolder('theme', __DIR__ . '/../templates/theme/', true);

        return $engine;
    };

    $container[PageProvider::class] = function (Container $container) : PageProvider {
        return new PageProvider(
            $container->get(Renderer::class),
            $container->get(EntityManager::class)
        );
    };

    $container[PostLocator::class] = function (Container $container) : PostLocator {
        return new PostLocator(
            $container->get(EntityManager::class),
            $container->get(ResponseFactoryInterface::class)
        );
    };

    $container[Renderer::class] = function (Container $container) : Renderer {
        return new Renderer($container->get(Engine::class));
    };

    $container[PostRepository::class] = function (Container $container) : PostRepository {
        return new DoctrinePostRepository(
            $container->get(EntityManager::class)
        );
    };

    $container[TagRepository::class] = function (Container $container) : TagRepository {
        return new DoctrineTagRepository(
            $container->get(EntityManager::class)
        );
    };

    $container[UserRepository::class] = function (Container $container) : UserRepository {
        return new DoctrineUserRepository(
            $container->get(EntityManager::class)
        );
    };

    $container[PostController::class] = function (Container $container) : PostController {
        return new PostController(
            $container->get(Renderer::class),
            $container->get(CommonMarkConverter::class),
            $container->get(PostValidator::class),
            $container->get(PostRepository::class),
            $container->get(TagRepository::class)
        );
    };

    $container[PostListController::class] = function (Container $container) : PostListController {
        return new PostListController(
            $container->get(Renderer::class),
            $container->get(PostRepository::class),
            $container->get(TagRepository::class)
        );
    };

    $container[ArchiveController::class] = function (Container $container) : ArchiveController {
        return new ArchiveController(
            $container->get(Renderer::class),
            $container->get(PostRepository::class),
            $container->get(ArchiveFormatter::class)
        );
    };

    $container[TagController::class] = function (Container $container) : TagController {
        return new TagController(
            $container->get(Renderer::class),
            $container->get(TagRepository::class)
        );
    };

    $container[LoginController::class] = function (Container $container) : LoginController {
        return new LoginController(
            $container->get(Renderer::class),
            $container->get(UserRepository::class),
            $container->get(Session::class)
        );
    };

    $container[RegistrationController::class] = function (Container $container) : RegistrationController {
        return new RegistrationController(
            $container->get(Renderer::class),
            $container->get(RegistrationValidator::class),
            $container->get(UserRepository::class),
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
        return new AuthMiddleware(
            $container->get(Session::class),
            $container->get(UserRepository::class),
            $container->get(ResponseFactoryInterface::class)
        );
    };

    $container[NotFoundMiddleware::class] = function (Container $container) : NotFoundMiddleware {
        return new NotFoundMiddleware(
            $container->get(Renderer::class)
        );
    };

    $container[SettingsProvider::class] = function (Container $container) : SettingsProvider {
        return new SettingsProvider(
            $container->get(Renderer::class),
            $container->get('settings')['sihae']
        );
    };

    $container[CsrfProvider::class] = function (Container $container) : CsrfProvider {
        return new CsrfProvider(
            $container->get(Renderer::class),
            $container->get(Guard::class)
        );
    };

    $container[Guard::class] = function (Container $container) : Guard {
        return new Guard(
            $container->get(ResponseFactoryInterface::class)
        );
    };

    $container[ResponseFactoryInterface::class] =
    $container[ServerRequestFactoryInterface::class] =
    $container[UriFactoryInterface::class] =
    $container[UploadedFileFactoryInterface::class] =
    $container[StreamFactoryInterface::class] = function (Container $container) : Psr17Factory {
        return new Psr17Factory();
    };

    $container[UserProvider::class] = function (Container $container) : UserProvider {
        return new UserProvider(
            $container->get(Renderer::class),
            $container->get(Session::class),
            $container->get(UserRepository::class)
        );
    };

    $container[Session::class] = function (Container $container) : Session {
        return new Session();
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

        $entityManager = EntityManager::create($settings['connection'], $config);

        if (getenv('APPLICATION_ENV') !== 'production') {
            \MacFJA\Tracy\DoctrineSql::init($entityManager);
        }

        return $entityManager;
    };

    // monolog
    $container['logger'] = function (Container $container) : LoggerInterface {
        $settings = $container->get('settings')['logger'];

        $logger = new Logger($settings['name']);

        $logger->pushProcessor(new UidProcessor());
        $logger->pushProcessor(new WebProcessor());

        $logger->pushHandler(new StreamHandler($settings['path'], $settings['level']));

        return $logger;
    };

    if (getenv('APPLICATION_ENV') === 'production') {
        $errorHandler = function (Container $container) : callable {
            return new ErrorController(
                $container->get('logger'),
                $container->get('response'),
                $container->get(Renderer::class)
            );
        };

        $container['errorHandler'] = $errorHandler;
        $container['phpErrorHandler'] = $errorHandler;
    } else {
        // in development we don't care about error handlers as Tracy will do it for us
        unset($container['errorHandler']);
        unset($container['phpErrorHandler']);
    }
};
