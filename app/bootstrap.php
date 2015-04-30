<?php

use Nette\Application\Routers\Route,
	Nette\Application\Routers\SimpleRouter;


// Load Nette Framework

if (@!include __DIR__ . '/../../Nette/loader.php') {
	die('Install Nette using `composer update`');
}



if (@!include __DIR__ . '/../vendor/autoload.php') {
	die('Install Nette using `composer update`');
}




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
// $router[] = new Route('[<lang=cs [a-z]{2}>/]<project>/<presenter>/<action>', "Dashboard:default");
$router[] = new Route('[<lang=cs [a-z]{2}>/]<presenter>/<action>[/<editID (\d+)>]', "Dashboard:default");
// $router[] = new Route('', "Error:default");
// $router[] = new Route('login', 'Sign:default');

return $container;
