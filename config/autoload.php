<?php

/**
 * Class NamespaceAutoloader
 *
 * Thanks to Andrea Turso (@trashofmasters) for making this original Class that I've adapted for this project.
 */
class NamespaceAutoloader
{
	/**
	 * @var string
	 */
	private $lookupDirectory;

	/**
	 *
	 */
	const SICMS_ROOT_NS = 'SiCMS';
	/**
	 *
	 */
	const DEFAULT_LOOKUP_DIR = './';

	/**
	 * @param string $lookupDirectory
	 */
	public function __construct($lookupDirectory = self::DEFAULT_LOOKUP_DIR)
	{
		$this->lookupDirectory = rtrim($lookupDirectory, DIRECTORY_SEPARATOR);
	}

	/**
	 * @param      $actualClassName
	 *
	 * @return bool|mixed
	 */
	public function load($actualClassName)
	{
		$className	= substr($actualClassName, 1 + strlen(self::SICMS_ROOT_NS));
		$fileName	= $this->lookupDirectory . DIRECTORY_SEPARATOR . trim($this->inflectFileName($className), '/');
		$fileExists = file_exists($fileName);

		if ($this->matchesRootNamespace($actualClassName))
		{
			if ($fileExists)
			{
				return require_once $fileName;
			}
			return false;
		}

		return true;
	}

	/**
	 * @param $actualClassName
	 *
	 * @return bool
	 */
	public function forwardToNextAutoloader($actualClassName)
	{
		$className	= substr($actualClassName, 1 + strlen(self::SICMS_ROOT_NS));

		if ($this->matchesRootNamespace($actualClassName))
		{
			spl_autoload_call($className);
			return true;
		}

		return false;
	}

	/**
	 * @param        $actualClassName
	 * @param string $extension
	 *
	 * @return string
	 */
	public function inflectFileName($actualClassName, $extension = '.php')
	{
		$className = trim($actualClassName, '\\');
		$inflectedClassName = str_replace('\\', DIRECTORY_SEPARATOR, $className);
		$inflectedPath = preg_replace('/([a-z])([A-Z])/', '$1_$2', $inflectedClassName);

		return strtolower($inflectedPath) . $extension;
	}

	/**
	 * @param $className
	 *
	 * @return string
	 */
	public function getClassName($className)
	{
		return substr($className, 1 + strrpos($className, '\\'));
	}

	/**
	 * @param $className
	 *
	 * @return string
	 */
	public function getNamespace($className)
	{
		return trim(substr($className, 0, strrpos($className, '\\')), '\\');
	}

	/**
	 * @param $className
	 *
	 * @return string
	 */
	public function getRootNamespace($className)
	{
		$className = trim($className, '\\');

		return trim(substr($className, 0, strpos($className, '\\')), '\\');
	}

	/**
	 * @param $className
	 *
	 * @return bool
	 */
	public function matchesRootNamespace($className)
	{
		$className = trim($className, '\\');
		$rootNamespace = trim(self::SICMS_ROOT_NS, '\\');

		return $rootNamespace == $this->getRootNamespace($className);
	}
}

$loader = new NamespaceAutoloader(__DIR__ . '/../src');

spl_autoload_register(
	function($className) use ($loader)
	{
		return $loader->load($className);
	}
);