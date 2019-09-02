<?php
return yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/main.php',
    require __DIR__ . '/main-local.php',
    require __DIR__ . '/test.php',
    [
        'components' => [
            'db' => [
                'class' => 'yii\db\Connection',
                'dsn' => 'mysql:host=mariadb;dbname=yii2advanced_test',
                'username' => 'dbuser',
                'password' => 'dbpass',
                'charset' => 'utf8mb4',
            ],
        ],
    ]
);
