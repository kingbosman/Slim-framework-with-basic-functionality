<?php 

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\User;
use Respect\Validation\Validator as v;

class AuthController extends Controller { 

	public function getSignOut($request, $response) {

		$this->auth->logOut();

		$this->flash->addMessage('info', 'You have been logged out');
		return $response->withRedirect($this->router->pathFor('home'));
		
	}

	public function getSignUp($request, $response){

		return $this->view->render($response, 'auth/signup.twig');
		
	}

	public function getSignIn($request, $response){

		return $this->view->render($response, 'auth/signin.twig');
		
	}

	public function postSignUp($request, $response){

		$validation = $this->validator->validate($request, [
			'email' => v::noWhiteSpace()->notEmpty()->email()->emailAvailable(),
			'name' => v::notEmpty()->alpha(),
			'password' => v::noWhiteSpace()->notEmpty(),
		]);

		if ($validation->failed()) {

			return $response->withRedirect($this->router->pathFor('auth.signup'));

		}

		$user = User::create([
			'user_email' => $request->getParam('email'),
			'user_name' => $request->getParam('name'),
			'user_password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
		]);

		$this->flash->addMessage('success', 'You have been signed up!');

		$this->auth->attempt($user->user_email, $request->getParam('password'));

		return $response->withRedirect($this->router->pathFor('home'));
		
	}

	public function postSignIn($request, $response) {

		$validation = $this->validator->validate($request, [
			'email' => v::noWhiteSpace()->notEmpty()->email(),
			'password' => v::noWhiteSpace()->notEmpty(),
		]);

		if ($validation->failed()) {

			return $response->withRedirect($this->router->pathFor('auth.signin'));

		}

		$auth = $this->auth->attempt(
			$request->getParam('email'),
			$request->getParam('password')
		);

		if (!$auth) {

			$this->flash->addMessage('danger', 'Incorrect details');
			return $response->withredirect($this->router->pathFor('auth.signin'));

		}

		$this->flash->addMessage('info', 'You have been signed in');
		return $response->withRedirect($this->router->pathFor('home'));

	}

}