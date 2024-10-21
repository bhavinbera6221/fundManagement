<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'FpnwRQbT0Rcm_SNiYYlss4oUiwxm3ome',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true, // Enable this for web portal
            'enableSession' => true,
            'loginUrl' => ['site/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\mail\Mailer',
            'viewPath' => '@app/mail',
            'useFileTransport' => true,// Set to false to send real emails
            // 'transport' => [
            //     'class' => 'Swift_SmtpTransport',
            //     'host' => 'smtp.example.com', // Your SMTP server
            //     'username' => 'your-email@example.com',
            //     'password' => 'your-password',
            //     'port' => '587', // Adjust based on your SMTP server
            //     'encryption' => 'tls',
            // ],
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
        'session' => [
            'class' => 'yii\web\Session',
            'timeout' => 3600, // Time in seconds (1 hour)
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'GET api/users' => 'api/get-users',
                'POST api/login' => 'api/login',
                'POST api/logout' => 'api/logout',
                'POST api/resetPassword' => 'api/change-password',
                'POST api/forgotPassword' => 'api/forgot-password',
            ],
    ],

    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
