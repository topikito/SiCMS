<?php

namespace SiCMS\Config;

use \Symfony\Component\Yaml\Yaml;

/**
 * Class ConfigLoader
 */
class ConfigLoader
{

	/**
	 * @var \Silex\Application
	 */
	protected $_app;

	/**
	 * @param \Silex\Application $_app
	 */
	function __construct(\Silex\Application $_app)
	{
		$this->_app = $_app;
	}

	/**
	 * @param string $resource
	 *
	 * @return array
	 */
	public function load($resource)
	{
		$configValues = Yaml::parse($resource);

		defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'dev'));
		$common = $configValues['common'];
		$env = $configValues[APPLICATION_ENV]? $configValues[APPLICATION_ENV] : [];

		$config = array_merge($common,$env);
		$this->_app['config'] = $config;

		return $config;
	}

}