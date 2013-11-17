<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/config_loader.php';
require_once __DIR__ . '/router.php';

/**
 * Class Bootstrap
 */
class Bootstrap extends \SilexMVC\Bootstrap
{

	function loadDispatcher()
	{
		$router = new Router($this->_app);
		$router->load();
	}

	function getConfigLoader()
	{
		return new ConfigLoader($this->_app);
	}

}

$params = [
	'configFile' => 'config.yml',
	'configPaths' => __DIR__
];

return Bootstrap::load($params);