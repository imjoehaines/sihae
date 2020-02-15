<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Setup;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$settings = require 'settings.php';
$doctrineSettings = $settings['settings']['doctrine'];

$config = Setup::createAnnotationMetadataConfiguration(
    $doctrineSettings['entity_path'],
    $doctrineSettings['auto_generate_proxies'],
    $doctrineSettings['proxy_dir'],
    $doctrineSettings['cache'],
    false
);

$entityManager = EntityManager::create($doctrineSettings['connection'], $config);

return ConsoleRunner::createHelperSet($entityManager);
