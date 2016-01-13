<?php

$params = require(__DIR__ . '/params.php');
require(__DIR__ . '/../common/functions/function.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'layout'    => 'admin',
    'defaultRoute' => 'site',
    'runtimePath' => '/data/web/Runtime',
    'language' => 'zh-CN',
    'bootstrap' => ['log'],
    'modules' => [
            'code' => [
                'class' => 'app\modules\code\Code'
            ],
            'gii' => [
            'class' => 'yii\gii\Module',
            'allowedIPs' => ['127.0.0.1'] // 按需调整这里
        ],
    ],
    'components' => [
        'assetManager'=>[
            // 设置存放assets的文件目录位置
            'basePath'=>'/data/web/yii/assets',
            // 设置访问assets目录的url地址
            'baseUrl'=>''
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'Y8coUSOKcOFxXJN4GY3nXT7cVLm-Box1',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        
                    ],
                ],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
