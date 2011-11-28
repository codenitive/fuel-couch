<?php

Namespace Couch;

import('phponcouch/couch', 'vendor');
import('phponcouch/couchClient', 'vendor');
import('phponcouch/couchDocument', 'vendor');

use \couch;
use \couchClient;
use \couchDocument;

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
class Document 
{

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
	 * $db = \Couch\Document::forge();
	 *
	 * You can also make multiple connection by adding the connection name as a parameter
	 * $name = 'qa';
	 * $db = \Couch\Document::forge($name);
	 *
	 * @access public
	 * @param string $name
	 * @return object couchDocument
	 */
	public static function forge($name = null) 
	{
		if (empty($name)) 
		{
			$name = \Config::get('db.active');
		}
		
		if ( ! isset(static::$instances[$name])) 
		{
		
			if (($client = Client::make($name)) === false) 
			{
				throw new \FuelException(__METHOD__.": Unable to initiate Couch\\Client");
			}
			
			try 
			{
				static::$instances[$name] = new \couchDocument($client);
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
	 * $db = \Couch\Document::make();
	 *
	 * You can also make multiple connection by adding the connection name as a parameter
	 * $name = 'qa';
	 * $db = \Couch\Document::make($name);
	 *
	 * @access public
	 * @param string $name
	 * @return object couchDocument
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
	 * @return  object  \Couch\Document
	 * @see     self::forge()
	 */
	public static function factory($name = null)
	{
		return static::forge($name);
	}

}