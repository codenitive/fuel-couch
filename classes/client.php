<?php

Namespace Couch;

import('phponcouch/couch', 'vendor');
import('phponcouch/couchClient', 'vendor');

use \couch;
use \couchClient;

/**
 * Implement a bridge to use PHP-on-Couch with FuelPHP. This
 * is an alternative option if you project require the use of PHP-on-Couch Library.
 *
 * The DB class use the configuration from /app/config/db.php to make it easier
 * to use. All you need to do is download PHP-on-Couch library and place it in /fuel/vendor/phponcouch
 *
 * From there you can just start using PHP-on-Couch by enabling 'couch' package from /app/config/config.php
 *
 *
 * @author Mior Muhammad Zaki <crynobone@gmail.com>
 */
class Client {

	/**
	 * @staticvar   Cache all instances
	 */
	protected static $instances = array();

	/**
	 * Initiated once from \Fuel\Core\Autoloader
	 *
	 * @static
	 * @access  public
	 */
	public static function _init() 
	{
		\Config::load('db', true);
	}

	/**
	 * Accessing Couch Library:
	 * $db = \Couch\Client::forge();
	 *
	 * You can also make multiple connection by adding the connection name as a parameter
	 * $name = 'qa';
	 * $db = \Couch\Client::forge($name);
	 *
	 * @access public
	 * @param string $name
	 * @return object couchClient
	 */
	public static function forge($name = null) 
	{
		if (empty($name)) 
		{
			$name = \Config::get('db.active');
		}

		if ( ! isset(static::$instances[$name])) 
		{
			$config = \Config::get("db.{$name}");

			if (null === $config) 
			{
				throw new \FuelException(__METHOD__.": Unable to get configuration for {$name}");
			}

			if ('couch' === $config['type']) 
			{
				throw new \FuelException(__METHOD__.": Configuration is not meant for CouchDB");
			}

			try 
			{
				static::$instances[$name] = new \couchClient($config['connection']['dsn'], $config['connection']['database']);
			} 
			catch (\FuelException $e) 
			{
				throw new \FuelException($e->getMessage());
			}
		}

		return static::$instances[$name];
	}

	/**
	 * Accessing Couch Library:
	 * $db = \Couch\Client::make();
	 *
	 * You can also make multiple connection by adding the connection name as a parameter
	 * $name = 'qa';
	 * $db = \Couch\Client::make($name);
	 *
	 * @access public
	 * @param string $name
	 * @return object couchClient
	 */
	public static function make($name = null) 
	{
		return static::forge($name);
	}

	/**
	 * Alias to self::forge()
	 *
	 * @static
	 * @access  public
	 * @param   string  $name
	 * @return  object  \Couch\Client
	 * @see     self::forge()
	 */
	public static function factory($name = null)
	{
		\Log::warning('This method is deprecated. Please use a forge() instead.', __METHOD__);

		return static::forge($name);
	}

}