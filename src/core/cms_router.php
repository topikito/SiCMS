<?php

namespace SiCMS\Core;

use \SiCMS\Controllers;

/**
 * Class CmsRouter
 *
 * @package SiCMS\Core
 */
class CmsRouter
{

	/**
	 * @var
	 */
	protected $_app;

	/**
	 * @param $_app
	 */
	function __construct($_app)
	{
		$this->_app = $_app;
	}

	protected function _loadHome()
	{
		$this->_app->get('/', function ()
		{
			$con = new Controllers\Home($this->_app);
			return $con->index();
		});

		return $this;
	}

	/**
	 * @return $this
	 */
	public function load()
	{
		$this->_loadHome();

		return $this;
	}

}