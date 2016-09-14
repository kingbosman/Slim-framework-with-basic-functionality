<?php 

namespace App\Controllers\Frontend;

use App\Controllers\Controller;
use App\Models\User as user_model;
use Respect\Validation\Validator as v;

class LoginController extends Controller { 

	public function main($request, $response) {

		$this->renderView($response, 'admin/login/content');
		
	}

	public function login($request, $response) {

		$validation = $this->validator->validate($request, [
			'email' => v::noWhiteSpace()->notEmpty()->email(),
			'password' => v::noWhiteSpace()->notEmpty(),
		]);

		if ($validation->failed()) {

			return $response->withRedirect($this->router->pathFor('login'));

		}

		$auth = $this->auth->attempt(
			$request->getParam('email'),
			$request->getParam('password')
		);

		if (!$auth) {

			$this->flash->addMessage('danger', 'Incorrect details');
			return $response->withredirect($this->router->pathFor('login'));

		}

		$this->flash->addMessage('info', 'You have been signed in');
		return $response->withRedirect($this->router->pathFor('home'));

	}

}