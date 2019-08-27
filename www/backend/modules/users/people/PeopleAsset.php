<?php

namespace app\modules\users\people;

use yii\web\AssetBundle;

/**
 * PeopleAsset наследует основной пакет приложений для бэкэнд-приложений.
 * @see https://www.yiiframework.com/doc/api/2.0/yii-web-assetbundle
 */
class PeopleAsset extends AssetBundle {

    /**
     *
     * @see https://www.yiiframework.com/doc/api/2.0/yii-web-assetbundle#$sourcePath-detail
     * @var string Содержит исходные файлы ресурсов для модуля
     */
    public $sourcePath = '@people-assets';

    /**
     *
     * @see https://www.yiiframework.com/doc/api/2.0/yii-web-assetbundle#$css-detail
     * @var array Список css-файлов подключеных к модулю
     */
    public $css = [
//        'css/select2.min.css',
    ];

    /**
     *
     * @see https://www.yiiframework.com/doc/api/2.0/yii-web-assetbundle#$js-detail
     * @var array Список js-файлов подключеных к модулю
     */
    public $js = [
//        'js/select2.full.min.js',
        'js/jquery.inputmask.js',
        'js/jquery.inputmask.date.extensions.js',
        'js/jquery.inputmask.extensions.js',
        'js/user.js',
    ];

    /**
     * Список имен классов пакетов-зависимостей для модуля
     * @see https://www.yiiframework.com/doc/api/2.0/yii-web-assetbundle#$depends-detail
     * @var array Список имен классов пакетов-зависимостей для модуля
     */
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}
