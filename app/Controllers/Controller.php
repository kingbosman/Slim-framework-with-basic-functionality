<?php 

namespace App\Controllers;

class Controller {

	protected $container;

	public function __construct($container){
	
		$this->container = $container;
	
	}

	public function __get($property){
	
		if ($this->container->{$property}) {

			return $this->container->{$property};

		}
	
	}

	public function renderView($response, $page, $args = []) {
	
		return $this->view->render($response, $page . '.twig', $args);
		
	} 

}