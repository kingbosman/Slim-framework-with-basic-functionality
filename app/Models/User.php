<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\model;

class User extends Model {
	
	protected $table = 'users';

	protected $fillable = [
		'email',
		'password',
	];

	public function setpassword($password, $user_id) {

		$this->where('id', $user_id)->update([
			'password' => password_hash($password, PASSWORD_DEFAULT)
		]);
		
	}

	public function getById($user_id) {
	
		return $this->where('id', $user_id)->first();
		
	} 

	public function getByEmail($email) {
	
		$result = $this->where('email', $email)->first();

		if (!$result) return false;

		return $result;
		
	} 

	public function newUser($email, $password) {
	
		return $this->create([
			'email' => $email,
			'password' => password_hash($password, PASSWORD_DEFAULT),
		]);
		
	} 

}