<?php
 use yii\gii\Module;
$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
    'id' => 'basic',
	//New
	'name' => 'Personerias',
    //Fin new
	'basePath' => dirname(__DIR__),
	//New
	'language' => 'es',
	//Fin new
    'bootstrap' => ['log'],
    'components' => [
        'reCaptcha' => [
               'name' => 'reCaptcha',
               'class' => 'himiklab\yii2\recaptcha\ReCaptcha',
               'siteKey' => '6LddSmEUAAAAAC9Q9DiOUrBUkEMZfnAFVfvUAoLN',
               'secret' => '6LddSmEUAAAAAOaDssk_B7mDJ-U9m9fDmrMXmiXQ',
               ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
		'enableCookieValidation' => true,
            'cookieValidationKey' => 'PersoneriasAbc1234',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
            //'authTimeout' => 20*60,
            'authTimeout' =>45*60,
             // auth expire 1 dia
            'loginUrl' =>'http://personerias.valledelcauca.gov.co/web/',
        ],
        'session' => [
            'class' => 'yii\web\Session',
            'cookieParams' => ['httponly' => true, 'lifetime' => 86400], // duracion de los cookies 86400 segundos = 1  dia
            'timeout' => 86400, //session expire 86400 segundos = 1 dia
            'useCookies' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            //'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            //'useFileTransport' => true,
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'secretariaticvalledelcauca@gmail.com',
		'password' => 'qhdnuwmjxaocrnjc',
                'port' => '465',
                'encryption' => 'ssl',
                    'streamOptions' => [ 'ssl' =>
                        [ 'allow_self_signed' => true,
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                        ],
                    ]
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
        'urlManager' => [
        'class' => 'yii\web\UrlManager',
        // Disable index.php
        'showScriptName' => false,
        // Disable r= routes
        'enablePrettyUrl' => false,
        'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
        ),
        ],

    ],
    'params' => $params,
];

 /*if (YII_ENV_DEV) {
     configuration adjustments for 'dev' environment
   $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
     'class' => 'yii\debug\Module',
         uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
         uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
 }*/


return $config;
