<?php

include __DIR__ . '/../src/core/cms_autoload.php';

class Autoload extends \SiCMS\Core\CmsAutoload
{
}

$loader = new Autoload(__DIR__ . '/../src');

spl_autoload_register(
	function($className) use ($loader)
	{
		return $loader->load($className);
	}
);