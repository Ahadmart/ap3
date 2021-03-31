<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'AhadPOS3',
    // preloading 'log' component
    'preload' => array('log'),
    // auto migration
    'commandMap' => array(
        'migrate' => array(
            'class' => 'system.cli.commands.MigrateCommand',
            'interactive' => 0,
            'migrationTable' => 'migrasi_db'
        ),
    ),
    'import' => array(
        'application.components.*',
        'application.models.*',
    ),
    // application components
    'components' => array(
        /*
          'db'=>array(
          'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
          ),
         */
        // uncomment the following to use a MySQL database

        'db' => require(dirname(__FILE__) . '/db.php'),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            ),
        ),
    ),
);
