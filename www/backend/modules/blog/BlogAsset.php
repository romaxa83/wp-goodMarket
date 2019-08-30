<?php

namespace app\modules\blog;

use yii\web\AssetBundle;

/**
 * BlogAsset наследует основной пакет приложений для бэкэнд-приложений.
 * @see https://www.yiiframework.com/doc/api/2.0/yii-web-assetbundle
 */
class BlogAsset extends AssetBundle {
    /**
     * Содержит исходные файлы ресурсов для модуля
     * @see https://www.yiiframework.com/doc/api/2.0/yii-web-assetbundle#$sourcePath-detail
     * @var string
     */
    public $sourcePath = '@blog-assets';
    /**
     * Список css-файлов подключеных к модулю
     * @see https://www.yiiframework.com/doc/api/2.0/yii-web-assetbundle#$css-detail
     * @var array
     */
    public $css = [
        'css/blog.css'
    ];
    /**
     * Список js-файлов подключеных к модулю
     * @see https://www.yiiframework.com/doc/api/2.0/yii-web-assetbundle#$js-detail
     * @var array
     */
    public $js = [
        'js/blog.js'
    ];
    /**
     * Список имен классов пакетов-зависимостей для модуля
     * @see https://www.yiiframework.com/doc/api/2.0/yii-web-assetbundle#$depends-detail
     * @var array
     */
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}
