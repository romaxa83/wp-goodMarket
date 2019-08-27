<?php

namespace app\modules\users\administrators;

use yii\web\AssetBundle;

/**
 * AdministratorsAsset наследует основной пакет приложений для бэкэнд-приложений.
 * @see https://www.yiiframework.com/doc/api/2.0/yii-web-assetbundle
 */
class AdministratorsAsset extends AssetBundle {

    /**
     *
     * @see https://www.yiiframework.com/doc/api/2.0/yii-web-assetbundle#$sourcePath-detail
     * @var string Содержит исходные файлы ресурсов для модуля
     */
    public $sourcePath = '@administrators-assets';

    /**
     *
     * @see https://www.yiiframework.com/doc/api/2.0/yii-web-assetbundle#$css-detail
     * @var array Список css-файлов подключеных к модулю
     */
    public $css = [
    ];

    /**
     * Список js-файлов подключеных к модулю
     * @see https://www.yiiframework.com/doc/api/2.0/yii-web-assetbundle#$js-detail
     * @var array Список js-файлов подключеных к модулю
     */
    public $js = [
        'js/administrators.js'
    ];

    /**
     *
     * @see https://www.yiiframework.com/doc/api/2.0/yii-web-assetbundle#$depends-detail
     * @var array Список имен классов пакетов-зависимостей для модуля
     */
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}
