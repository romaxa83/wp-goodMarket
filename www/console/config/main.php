<?php

$params = array_merge(
        require __DIR__ . '/../../common/config/params.php', require __DIR__ . '/../../common/config/params-local.php', require __DIR__ . '/params.php', require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
        ],
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationNamespaces' => [
                'backend\modules\product\migrations',
                'backend\modules\banners\migrations',
                'backend\modules\content\migrations',
                'backend\modules\content\migrations\projectMigrations',
                'backend\modules\blog\migrations',
                'backend\modules\banners\migrations',
                'backend\modules\order\migrations',
                'backend\modules\category\migrations',
                'backend\modules\import\migrations',
                'backend\modules\reviews\migrations'
            ],
        ],
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'modules' => [
        'filemanager' => [
            'class' => 'backend\modules\filemanager\FileManager',
        ]
    ],
    'params' => $params,
];
