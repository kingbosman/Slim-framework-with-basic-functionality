<?php 

namespace App\Controllers\Frontend;

use App\Controllers\Controller;
Use Slim\Views\Twig as View;
use App\Models\Page as page_model;

class HomeController extends Controller { 

	private $model;

	public function main($request, $response) {

		$this->model = new \stdClass();
		$this->model->page = new Page_model;

		$this->renderView($response, 'frontend/home/content', [
			'test' => 'hello world!',
		]);

	}

}