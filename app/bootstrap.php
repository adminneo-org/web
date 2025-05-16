<?php

declare(strict_types=1);

use Nette\Bootstrap\Configurator;

// Paths.
define('BASE_DIR', realpath(__DIR__ . '/..'));
const APP_DIR = BASE_DIR . '/app';
const CONFIG_DIR = BASE_DIR . '/config';
const VENDOR_DIR = BASE_DIR . '/vendor';
const COMPONENTS_DIR = BASE_DIR . '/vendor_components';
const LOG_DIR = BASE_DIR . '/log';
const PUBLIC_DIR = BASE_DIR . '/public';
const TEMP_DIR = BASE_DIR . '/tmp';
const CACHE_DIR = TEMP_DIR . '/cache';

// Detect and set environment.
if (file_exists(CONFIG_DIR . '/environment.php')) {
    require_once CONFIG_DIR . '/environment.php';
}

if (!defined('ENVIRONMENT_NAME')) {
    define('ENVIRONMENT_NAME', 'production');
}

// Load Libraries.
require VENDOR_DIR . '/autoload.php';

// Configure application.
$configurator = new Configurator();
$configurator->setDebugMode(ENVIRONMENT_NAME == 'development');
$configurator->enableTracy(LOG_DIR);

// Enable RobotLoader - this will load all classes automatically.
$configurator->setTempDirectory(TEMP_DIR);
$configurator->createRobotLoader()
    ->addDirectory(APP_DIR)
    ->register();

// Create Dependency Injection container from config.neon file.
$configurator->addConfig(CONFIG_DIR . '/config.neon');
if (file_exists(CONFIG_DIR . '/config.local.neon')) {
    $configurator->addConfig(CONFIG_DIR . '/config.local.neon');
}

// Run the application.
$container = $configurator->createContainer();
$application = $container->getByType('Nette\Application\Application');

if (ENVIRONMENT_NAME == 'production') {
    $application->errorPresenter = 'Web:Error';
}

$application->run();
