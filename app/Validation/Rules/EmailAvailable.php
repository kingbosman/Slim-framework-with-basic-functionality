<?php 

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use App\Models\User as user_model;

class EmailAvailable extends AbstractRule {

	private $model;

	public function __construct() {
	
		$this->model = new \stdClass();
		$this->model->users = new user_model;
		
	}

	public function validate($email) {

		if (!$this->model->users->getByEmail($email)) return true;
		
	}
		
}