<?php 

Use Respect\Validation\Validator as v;

die('Please go to bootstrap/app.php and set db + comment this die() line.');

session_start();

require __DIR__ . '/../vendor/autoload.php';

/* DB settings in config */
$app = new \Slim\App([

	'settings' => [

		'displayErrorDetails' => true,

	],
	'db' => [
		'driver' => 'mysql',
		'host' => 'xxx',
		'database' => 'xxx',
		'username' => 'xxx',
		'password' => 'xxx',
		'charset' => 'utf8',
		'collation' => 'utf8_unicode_ci',
		'prefix' => '',
	]

]);

/* Set containers */
$container = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function ($container) use ($capsule) {

	return $capsule;

};

$container['auth'] = function ($container) {

	return new App\Auth\User;

};

$container['page'] = function ($container) {

	return new App\Models\Page;

};

$container['flash'] = function ($container) {

	return new \Slim\Flash\Messages;

};

$container['view'] = function ($container) { 

	$view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [ 

		'cache' => false,

	]);

	$view->addExtension(new \Slim\Views\TwigExtension(

		$container->router,
		$container->request->getUri()

	));

	$view->getEnvironment()->addGlobal('user', [
		'check' => $container->auth->check(),
	]);

	/* Add global for future use */
	$view->getEnvironment()->addGlobal('flash', $container->flash);

	return $view;

};

$container['validator'] = function ($container) {

	return new App\Validation\Validator;

};

$container['csrf'] = function ($container) {

	return new \Slim\Csrf\Guard;

};



/* find all controllers in page table */
foreach ($container['page']->getActive() as $page) {

	$name = ucfirst($page['name']) . 'Controller';
	$type = $page['type'];

	$container[$name] = function($container) use ($name, $type) { 

		$path = '\\App\Controllers\\' . $type . '\\';

		$path = $path . $name;

		return new $path($container);

	};

}

/* Add middleware */
$app->add(new \App\Middleware\ValidationErrorMiddleware($container));
$app->add(new \App\Middleware\OldInputMiddleware($container));
$app->add(new \App\Middleware\CsrfViewMiddleware($container));

$app->add($container->csrf);

v::with('App\\Validation\\Rules\\');
 
require __DIR__ . '/../app/routes.php';

