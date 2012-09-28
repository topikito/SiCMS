<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/cms_autoload.php';

class Bootstrap
{

	protected $_config;
	protected $_app;

	private function __construct($config)
	{
		$this->_app = new Silex\Application();
		$this->_config = $config;
	}

	public function loadConfig()
	{
		/** CONFIG * */
		$this->_app->register(new Silex\Provider\TwigServiceProvider(), array(
			'twig.path' => __DIR__ . '/../src/views',
		));
		$this->_app->register(new Silex\Provider\TranslationServiceProvider(), array(
			'translator.messages' => array()
		));
		$this->_app->register(new Silex\Provider\DoctrineServiceProvider(), array(
			'db.options' => array(
				'driver' => 'pdo_mysql',
				'host' => $this->_config['database.host'],
				'dbname' => $this->_config['database.name'],
				'user' => $this->_config['database.user'],
				'password' => $this->_config['database.password'],
			),
		));
		$this->_app['debug'] = $this->_config['debug.mode'];
		return $this;
	}

    private function loadDispatcher()
    {
        CmsObject::setApplication($this->_app);
        CmsObject::setConfig($this->_config);

        //TODO: Improve this and use the Silex native way
        list($firstName) = explode('.',$_SERVER['SERVER_NAME']);

        switch ($firstName)
        {
            case 'api':
                $this->loadApiDispatcher();
                break;

            default:
                $this->loadDefaultDispatcher();
        }
        return $this;
    }

    public function loadApiDispatcher()
    {
        //TODO: Change '/' for '/paste' or '/save'. Maybe use "PUT" instead
        $this->_app->post('/', function ()
        {
            $con = new CodeApiController(array('callingFrom' => 'api'));
            return $con->saveCode();
        });

        $this->_app->get('/{id}', function ($id)
        {
            $con = new CodeApiController(array('callingFrom' => 'api'));
            return $con->viewCode($id);
        });

        return $this;
    }

	public function loadDefaultDispatcher()
	{
        /** POSTERS **/
        $this->_app->post('/', function ()
        {
            $con = new CodeController();
            return $con->saveCode();
        });

        /** GETTERS */
		$this->_app->get('/', function ()
			{
				$con = new CodeController();
				return $con->index();
			});

		$this->_app->get('/{id}', function ($id)
			{
				$con = new CodeController();
				return $con->viewCode($id);
			});

		$this->_app->get('/{id}/raw', function ($id)
			{
				$con = new CodeController();
				return $con->viewRaw($id);
			});

		return $this;
	}

	public function getApplication()
	{
		return $this->_app;
	}

	static public function load($config)
	{
		$me = new static($config);
		$me->loadConfig()->loadDispatcher();
		return $me->getApplication();
	}

}

$config = parse_ini_file('config.ini', true);
define('ENV', $config['cms.env']);
return Bootstrap::load($config[ENV]);
