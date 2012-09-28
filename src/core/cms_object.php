<?php

abstract class CmsObject
{
	static protected $_application;
    static protected $_configuration;

	public static function setApplication($app)
	{
		self::$_application = $app;
	}

    public static function setConfig($config)
    {
        self::$_configuration = $config;
    }
}
