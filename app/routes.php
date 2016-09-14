<?php 

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

/* Routes for all */
$app->get('/', 'HomeController:main')->setName('home');

/* Guest only routes */
$app->group('', function () {

	/* Login */
	$this->get('/login', 'LoginController:main')->setName('login');
	$this->post('/login', 'LoginController:login');

	/* Register */
	$this->get('/register', 'RegisterController:main')->setName('register');
	$this->post('/register', 'RegisterController:register');

})->add(new GuestMiddleware($container));

/* Admin routes */
$app->group('', function () {

	/* Logout */
	$this->get('/logout', function ($request, $response) {

	    $this->auth->logout();
	    $this->flash->addMessage('info', 'You have been logged out');
		return $response->withRedirect($this->router->pathFor('home'));

	})->setName('admin.logout');

})->add(new AuthMiddleware($container));