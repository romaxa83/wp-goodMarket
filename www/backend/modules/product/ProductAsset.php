<?php

namespace backend\modules\product;

use yii\web\AssetBundle;

/**
 * Класс MenuAsset наследует основной класс AssetBundle для работы с ресурсами проекта.
 * @see https://www.yiiframework.com/doc/api/2.0/yii-web-assetbundle
 */
class ProductAsset extends AssetBundle {

    /**
     * @see https://www.yiiframework.com/doc/api/2.0/yii-web-assetbundle#$sourcePath-detail
     * @var string Содержит исходные файлы ресурсов для модуля.
     */
    public $sourcePath = '@product-assets';

    /**
     * @see https://www.yiiframework.com/doc/api/2.0/yii-web-assetbundle#$css-detail
     * @var array Список css-файлов подключеных к модулю.
     */
    public $css = [
        'css/product.css'
    ];

    /**
     * @see https://www.yiiframework.com/doc/api/2.0/yii-web-assetbundle#$js-detail
     * @var array Список js-файлов подключеных к модулю.
     */
    public $js = [
        'js/jquery.cookie.js',
        'js/product.js',
        'js/gallery.js'
    ];

    /**
     * @see https://www.yiiframework.com/doc/api/2.0/yii-web-assetbundle#$depends-detail
     * @var array Список имен классов пакетов-зависимостей для модуля.
     */
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}
