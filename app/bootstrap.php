<?php

use Nette\Application\Routers\Route,
	Nette\Application\Routers\SimpleRouter;


	require __DIR__ . '/../vendor/autoload.php';
/*
// Load Nette Framework
if (@!include __DIR__ . '/../../Nette/loader.php') {
	die('Install Nette using `composer update`');
}
*/

// Configure application
$configurator = new Nette\Configurator;

$configurator->setDebugMode(true);

// Enable Nette Debugger for error visualisation & logging
$configurator->enableDebugger(__DIR__ . '/../../log');

// Enable RobotLoader - this will load all classes automatically
$configurator->setTempDirectory(__DIR__ . '/../../temp');
$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->register();

// Create Dependency Injection container from config.neon file
$configurator->addConfig(__DIR__ . '/config.neon');
$container = $configurator->createContainer();

// Setup router using mod_rewrite detection
$router = $container->getService('router');
$router[] = new Route('index.php', 'Dashboard:default', Route::ONE_WAY);
$router[] = new Route('<presenter>/<action>[/<id>]', 'Dashboard:default');
// $router[] = new Route('login', 'Sign:default');

return $container;
