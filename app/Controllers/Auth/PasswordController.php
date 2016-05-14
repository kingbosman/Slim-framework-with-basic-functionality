<?php 

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\User;
use Respect\Validation\Validator as v;

class PasswordController extends Controller { 

	public function getChangePassword($request, $response) {

		return $this->view->render($response, 'auth/password/change.twig');
		
	}

	public function postChangePassword($request, $response) {

		$validation = $this->validator->validate($request, [
			'password_old' => v::noWhiteSpace()->notEmpty()->matchesPassword($this->auth->user()->user_password),
			'password' => v::noWhiteSpace()->notEmpty(),
		]);

		if ($validation->failed()) {

			return $response->withRedirect($this->router->pathFor('auth.password.change'));

		}

		$this->auth->user()->setPassword($request->getParam('password'), $this->auth->user()->user_id);

		$this->flash->addMessage('info', 'Your password has been changed');
		return $response->withRedirect($this->router->pathFor('home'));
		
	}

}