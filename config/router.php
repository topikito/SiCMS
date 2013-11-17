<?php

class Router extends SilexMVC\Router
{
	public function load()
	{
		$this->_app->get('/', function()
		{
			$homeController = new \SiCMS\Controllers\Home($this->_app);
			$response = $homeController->index();

			return $response;
		});
	}

}