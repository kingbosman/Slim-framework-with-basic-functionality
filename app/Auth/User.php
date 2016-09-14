<?php 

namespace App\Auth;

use App\Models\User as user_model;

class User {

	private $model;

	public function __construct() {
	
		$this->model = new \stdClass();
		$this->model->users = new user_model;
		
	}

	public function user() {

		if (isset($_SESSION['user'])) {

			return $this->model->users->getById($_SESSION['user']);

		}
		
	}

	public function check() {

		return isset($_SESSION['user']);
		
	}

	public function attempt($email, $password) {

		$user = $this->model->users->getByEmail($email);

		if (!$user) {

			return false;

		}

		if (password_verify($password, $user->password)) {

			$_SESSION['user'] = $user->id;
			return true;

		}

		return false;
		
	}

	public function logOut() {

		unset($_SESSION['user']);
		
	}
		
}