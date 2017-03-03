<?php

use Nette\Application\Routers\Route,
	Nette\Application\Routers\SimpleRouter;


// Load Nette Framework

if (@!include __DIR__ . '/../Nette/loader.php') {
	die('Install Nette using `composer update`');
}

if (@!include __DIR__ . '/../vendor/autoload.php') {
	die('Install Nette using `composer update`');
}

// Configure application
$configurator = new Nette\Configurator;

$configurator->setDebugMode(array('46.174.23.40', '212.4.132.124', '178.217.146.180', '178.248.249.175', '77.48.26.73')); // enable for your remote IP

// Enable Nette Debugger for error visualisation & logging
$configurator->enableDebugger(__DIR__ . '/../log');

// Enable RobotLoader - this will load all classes automatically
$configurator->setTempDirectory(__DIR__ . '/../temp');
$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->register();

// Create Dependency Injection container from config.neon file
$envConf = isset($_SERVER['NETTE_PROJECT_MODULE_CONFIG']) ? $_SERVER['NETTE_PROJECT_MODULE_CONFIG'] : 'local';

$configurator->addConfig(__DIR__ . '/config.neon');
$configurator->addConfig(__DIR__ . '/config.' . $envConf . '.neon');

$container = $configurator->createContainer();

// FIXME as CP DI extension
// Texy hack
$baseUri = dirname($_SERVER['SCRIPT_NAME']);

if ($baseUri == '\\' || $baseUri == '/') {
	$baseUri = '';
}

// FIXME as CP DI extension
$configurator->addParameters(array('baseUri' => $baseUri));

// Setup router using mod_rewrite detection
$router = $container->getService('router');
// $router[] = new Route('[<lang=cs [a-z]{2}>/]<project>/<presenter>/<action>', "Dashboard:default");
/*
$router[] = new Route('//taskz/[<lang=cs [a-z]{2}>/]<projectID>-<projectName>/<taskListID>-<taskListName>/new/', "Task:new");
$router[] = new Route('//taskz/[<lang=cs [a-z]{2}>/]<projectID>-<projectName>/<taskListID>-<taskListName>/', "List:list");
$router[] = new Route('//taskz/[<lang=cs [a-z]{2}>/]<projectID>-<projectName>/new/', "List:new");
// $router[] = new Route('//taskz/[<lang=cs [a-z]{2}>/]<projectID>-<projectName>/task/new/', "Task:new");
$router[] = new Route('//taskz/[<lang=cs [a-z]{2}>/]<projectID>-<projectName>/', "List:default");
$router[] = new Route('//taskz/[<lang=cs [a-z]{2}>/]project/<action>', "Project:default");
*/
$router[] = new Route('/[<lang=cs [a-z]{2}>/]<presenter>/<action>[/<editID (\d+)>]', "Dashboard:default");


//$router[] = new Route('[<lang=cs [a-z]{2}>/]<presenter>/<action>[/<editID (\d+)>]', "Dashboard:default");
// $router[] = new Route('', "Error:default");
// $router[] = new Route('login', 'Sign:default');

return $container;
