<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Cinch - Digital Preservation Made Simple!',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		// used by rights module
		'application.modules.rights.*', 
		'application.modules.rights.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'',
		 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		), 
		// admin module created by Dean Farrell 11-23-11
		'admin',
		// used by rights module
		'rights'=>array(
			//'install'=>true,
			//'superuserName'=>'cinch_admin'
		),
	),

	// application components
	'components'=>array(
		'user'=>array(
			'class' => 'RWebUser',
			// enable cookie-based authentication
			'allowAutoLogin'=>false,
		),
		'authManager' => array(
			'class' => 'RDbAuthManager',
			'connectionID'=>'db',

		),
		// adding in CSRF protection component
		'request' => array(
			'enableCsrfValidation' => true
		),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		), */
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=testdrive',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
		
		// added to put session info into db
		'session' => array (
			'class' => 'system.web.CDbHttpSession',
			'connectionID' => 'db',
			'sessionTableName' => 'user_session_info',
		),
	
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				), */
				
			),
		),
		
		/*** 3rd party extentions ****/
		// CFile extension verion 0.9
		'file'=>array(
        	'class'=>'application.extensions.file.CFile',
        ),
        
        // added amqp support
        'amqp' => array(
            'class' => 'application.components.AMQP.CAMQP',
            'host'  => '127.0.0.1'
        ),
		
		'zip' => array(
			'class'=>'application.extensions.zip.EZip'
		),
		/**** end of 3rd party extentions ****/
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);