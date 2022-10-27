<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return [
    'basePath'          => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name'              => 'AhadPOS ۳', //'Ahad POS ۳',
    'theme'             => 'default_dark', // 'materialize',
    'defaultController' => 'app',
    // preloading 'log' component
    'preload' => ['log'],
    // autoloading model and component classes
    'import' => [
        'application.models.*',
        'application.components.*',
    ],
    'modules' => [
        // uncomment the following to enable the Gii tool
        'gii' => [
            'class'    => 'system.gii.GiiModule',
            'password' => 'abc',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => ['127.0.0.1', '192.168.1.98', '192.168.1.99', '::1'],
        ],
    ],
    // application components
    'components' => [
        'user' => [
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            'loginUrl'       => ['app/login'],
        ],
        // uncomment the following to enable URLs in path-format
        'urlManager' => [
            'urlFormat'      => 'path',
            'showScriptName' => false,
            'caseSensitive'  => false,
            'rules'          => [
                'tools/<controller:\w+>/<action:\w+>'    => 'tools/<controller>/<action>',
                '<controller:\w+>/<id:\d+>'              => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>'          => '<controller>/<action>',
            ],
        ],
        // MySQL database
        'db'           => require(dirname(__FILE__) . '/db.php'),
        'errorHandler' => [
            // action to display errors
            'errorAction' => 'app/error',
        ],
        'log' => [
            'class'  => 'CLogRouter',
            'routes' => [
                [
                    'class'  => 'CFileLogRoute',
                    'levels' => 'info, error, warning',
                ],
                // uncomment the following to show log messages on web pages
                /*
              array(
              'class' => 'CWebLogRoute',
              ),
             */
            ],
        ],
        'authManager' => [
            'class'        => 'CDbAuthManager',
            'connectionID' => 'db',
            'defaultRoles' => ['authenticated'],
        ],
        'ePdf' => [
            'class'  => 'ext.yii-pdf.EYiiPdf',
            'params' => [
                'mpdf' => [
                    'librarySourcePath' => 'application.vendors.mpdf.*',
                    'constants'         => [
                        '_MPDF_TEMP_PATH' => Yii::getPathOfAlias('application.runtime'),
                    ],
                    'class' => 'mpdf',
                ],
            ],
        ],
    ],
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => [
        // dipakai untuk authManager
        'superuser' => 'admin',

        // dipakai untuk jabatan user yang berstatus admin secara SOP
        'useradmin' => 'SUPERVISOR',

        // this is used in contact page
        'adminEmail' => 'webmaster@example.com',
    ],
];
