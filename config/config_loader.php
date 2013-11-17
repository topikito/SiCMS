<?php

/**
 * Class ConfigLoader
 */
class ConfigLoader extends SilexMVC\ConfigLoader
{

	public function getBaseDir()
	{
		return dirname(__DIR__);
	}

}