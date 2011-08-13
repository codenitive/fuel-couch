#PHP on Couch Package for FuelPHP

The following is a proposed implementation of PHP on Couch to be used with the FuelPHP framework.

##Proposed layout

	fuel
	fuel/core/
	fuel/core/vendor/
	fuel/core/vendor/phponcouch/couchClient.php
	fuel/core/vendor/phponcouch/couchDocument.php
	fuel/core/vendor/phponcouch/couchReplicator.php
	fuel/packages/
	fuel/packages/couch/
	fuel/packages/couch/classes/
	fuel/packages/couch/classes/client.php
	fuel/packages/couch/classes/document.php
	fuel/packages/couch/bootstrap.php

##How does it work

The couchdb class extends the couchClient class. Basically, it :
- includes the four PHP on Couch files
- overload the couchClient constructor to read CouchDB server data source name and database name from a config file, instead of getting it through constructor parameters.

##How to use it

- copy the PHP on Couch classes *couch.php*, *couchClient.php*, *couchDocument.php* and *couchReplicator.php* inside the **fuel/core/vendor** folder of your FuelPHP application.
- copy the *fuel/packages/couch/* folder into the **packages** folder of your FuelPHP application.
- edit the *fuel/app/config/db.php* file to set the two configuration options *dsn* and *database* :


    'dev' => array(
		'type'			=> 'couch',
		'connection'	=> array(
			'dsn'		=> 'http://username:password@localhost:5984/',
			'database'   => 'database_name',
			'persistent' => false,
		),
		'table_prefix' => '',
		'charset'      => 'utf8',
		'caching'      => false,
		'profiling'    => false,
	)
