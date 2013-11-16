<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/config_loader.php';
require_once __DIR__ . '/router.php';

/**
 * Class Bootstrap
 */
class Bootstrap extends SiCMS\Core\CmsBootstrap
{
}

return Bootstrap::load(['configFile' => 'config.yml']);