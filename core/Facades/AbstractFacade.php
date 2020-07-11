<?php


namespace ImpressCMS\Core\Facades;

/**
 * Defines base facade class
 *
 * @package ImpressCMS\Core\Facades
 */
abstract class AbstractFacade
{

	/**
	 * Gets instance of class
	 *
	 * @return static
	 */
	protected static function getInstance() {
		static $instance = null;
		if ($instance === null) {
			$db = \icms::getInstance()->get('db');
			$instance = new static($db);
		}
		return $instance;
	}

	/**
	 * @inheritDoc
	 */
	public static function __callStatic($name, $arguments)
	{
		return call_user_func_array([static::getInstance(), $name], $arguments);
	}
}