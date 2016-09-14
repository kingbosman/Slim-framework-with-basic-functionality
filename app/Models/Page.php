<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\model;

class Page extends Model {
	
	protected $table = 'pages';

	protected $fillable = [
		'name',
		'type',
		'active',
	];

	public function getActive() {
	
		return $this->where('active', 1)->get()->toArray();
		
	} 

	public function test() {
	
		return 'hhg';
		
	} 

}