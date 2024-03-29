<?php

$params = array_merge(
        require __DIR__ . '/../../common/config/params.php', require __DIR__ . '/../../common/config/params-local.php', require __DIR__ . '/params.php', require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'language' => 'ru',
    'modules' => [
        'blog' => [
            'class' => 'backend\modules\blog\Blog',
        ],
        'category' => [
            'class' => 'backend\modules\category\Category',
        ],
        'import' => [
            'class' => 'backend\modules\import\Import',
        ],
        'product' => [
            'class' => 'backend\modules\product\Product',
        ],
        'banners' => [
            'class' => 'backend\modules\banners\Banners',
        ],
        'order' => [
            'class' => 'backend\modules\order\Order',
        ],
        'seo' => [
            'class' => 'backend\modules\seo\Seo',
        ],
        'reviews' => [
            'class' => 'backend\modules\reviews\Reviews',
        ],
        'filemanager' => [
            'class' => 'backend\modules\filemanager\FileManager',
            'rename' => true,
        ],
        'content' => [
            'class' => 'backend\modules\content\Page',
        ],
        'settings' => [
            'class' => 'backend\modules\settings\Settings',
        ],

        'users' => [
            'class' => 'backend\modules\users\Users',
            'modules' => [
                'roles' => [
                    'class' => 'backend\modules\users\roles\Roles',
                ],
                'administrators' => [
                    'class' => 'backend\modules\users\administrators\Administrators',
                ],
                'people' => [
                    'class' => 'backend\modules\users\people\People',
                ],
            ],
        ],
    ],
    'name' => 'GoodMarket',
    'homeUrl' => '/admin',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'baseUrl' => '/admin',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManagerFrontend' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => '',
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'backend\modules\content\components\PageRule',
                    'connectionID' => 'db',
                ]
            ]
        ],
//        'urlManager' => [
//            'enablePrettyUrl' => true,
//            'showScriptName' => false,
//            'rules' => [
//            ],
//        ],
    ],
    'params' => $params,
];
