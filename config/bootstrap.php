<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/config_loader.php';

use Silex\Provider\TwigServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Symfony\Component\Config\FileLocator;
use SiCMS\Config\ConfigLoader;

/**
 * Class Bootstrap
 */
class Bootstrap
{

	/**
	 * @var Silex\Application
	 */
	protected $_app;

	/**
	 *
	 */
	private function __construct()
	{
		// Let the magic BEGIN!!
		$this->_app = new Silex\Application();
	}

	/**
	 * @param $configFile
	 *
	 * @return $this
	 */
	public function loadConfig($configFile)
	{
		$fileLocator = new FileLocator(__DIR__);

		$configLoader = new ConfigLoader($this->_app);
		$configLoader->load($fileLocator->locate($configFile));

		echo '<pre>';var_dump($this->_app['config']);die;

		/** CONFIG * */
		$this->_app->register(new TwigServiceProvider(), [
			'twig.path' => __DIR__ . '/../src/views'
		]);

		$this->_app->register(new TranslationServiceProvider(), [
			'translator.messages' => []
		]);

		$this->_app->register(new DoctrineServiceProvider(), [
			'db.options' => [
				'driver' => 'pdo_mysql',
				'host' => $this->_app['config']['database']['host'],
				'dbname' => $this->_app['config']['database']['name'],
				'user' => $this->_app['config']['database']['user'],
				'password' => $this->_app['config']['database']['password'],
			],
		]);

		$this->_app['debug'] = $this->_app['config']['debug']['mode'];
		return $this;
	}

	/**
	 * @return $this
	 */
	private function loadDispatcher()
	{
		//TODO: Improve this and use the Silex native way
		list($firstName) = explode('.', $_SERVER['SERVER_NAME']);

		switch ($firstName)
		{
			default:
				$this->loadDefaultDispatcher();
		}
		return $this;
	}

	/**
	 * @return $this
	 */
	public function loadDefaultDispatcher()
	{
		$this->_app->get('/', function ()
		{
			$con = new \SiCMS\Controllers\Code($this->_app);
			return $con->index();
		});

		return $this;
	}

	/**
	 * @return \Silex\Application
	 */
	public function getApplication()
	{
		return $this->_app;
	}

	/**
	 * @param array $params
	 *
	 * @return mixed
	 */
	static public function load($params = [])
	{
		$configRoute = null;

		extract($params);

		$me = new static();
		$me->loadConfig($configRoute)->loadDispatcher();
		return $me->getApplication();
	}

}

return Bootstrap::load(['configRoute' => 'config.yml']);
