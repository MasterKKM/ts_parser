<?php

$params = require __DIR__ . '/params.php';

/* Предотвращение случайной утечки регистрацонных данных сервера.
 * На проде данные базы данных храняться в /prod/db.php
 */
@$db = require __DIR__ . '/prod/db.php';
if (empty($db)) {
    $db = require __DIR__ . '/db.php';
}

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'traceLevel' => 3,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                'parserLoader' => [
                    'class' => 'yii\log\FileTarget',
                    'categories' => [
                        'app\models\console\*',
                    ],
                    'logVars' => [],
                    'exportInterval' => 1,
                    'logFile' => 'runtime/logs/parserLoader',
                ],
            ],
        ],
        'db' => $db,
    ],
    'params' => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
    'container' => [
        'definitions' => [
            'Loader' => [
                'class' => 'app\models\console\Loader',
                'startPage' => 0,
                'region' => '7600000000000',
                'updateOnChanged' => true,
                'clearOnStart' => true,
            ],
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
