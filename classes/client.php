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

    private static $instances = array();

    /**
     * Accessing Couch Library:
     * $db = \Couch\Client::factory();
     *
     * You can also make multiple connection by adding the connection name as a parameter
     * $name = 'qa';
     * $db = \Couch\Client::factory($name);
     *
     * @access public
     * @param string $name
     * @return object couchClient
     */
    public static function factory($name = null) {
        \Config::load('db', true);

        if (\empty($name)) 
        {
            $active = \Config::get('db.active');
            $name = $active;
        }

        if (!\isset(static::$instances[$name])) 
        {
            $config = \Config::get("db.{$name}");

            if (\is_null($config)) 
            {
                throw new \Fuel_Exception("Unable to get configuration for {$name}");
            }

            if ($config['type'] != 'couch') 
            {
                throw new \Fuel_Exception("Configuration is not meant for CouchDB");
            }

            try 
            {
                static::$instances[$name] = new \couchClient($config['connection']['dsn'], $config['connection']['database']);
            } 
            catch (\Fuel_Exception $e) 
            {
                throw new \Fuel_Exception($e);
            }
        }

        return static::$instances[$name];
    }

}