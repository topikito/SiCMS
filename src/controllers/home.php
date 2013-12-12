<?php

namespace SiCMS\Controllers;

class Home extends \SilexMVC\Controller
{
    protected   $_callingFrom = 'www';

	public function __construct($app)
	{
		parent::__construct($app);

        if (isset($params['callingFrom']))
        {
            $this->_callingFrom = $params['callingFrom'];
            switch ($this->_callingFrom)
            {
                case 'api':
                    $this->_typeOfView = 'json';
            }
        }
	}

	public function index()
	{
		return $this->_render('index.twig');
	}

}