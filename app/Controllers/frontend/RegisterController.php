<?php 

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\User as user_model;
use Respect\Validation\Validator as v;

class RegisterController extends Controller { 

	public function main($request, $response) {

		$this->renderView($response, 'admin/register/content');
		
	}

	public function register($request, $response) {

		$this->model = new \stdClass();
		$this->model->users = new user_model;

		$validation = $this->validator->validate($request, [
			'email' => v::noWhiteSpace()->notEmpty()->email()->emailAvailable(),
			'password' => v::noWhiteSpace()->notEmpty(),
		]);

		if ($validation->failed()) {

			return $response->withRedirect($this->router->pathFor('register'));

		}

		$user = $this->model->users->newUser($request->getParam('email'), $request->getParam('password'));

		$this->flash->addMessage('success', 'You have been signed up!');

		$this->auth->attempt($user->email, $request->getParam('password'));

		return $response->withRedirect($this->router->pathFor('home'));
		
	}

}