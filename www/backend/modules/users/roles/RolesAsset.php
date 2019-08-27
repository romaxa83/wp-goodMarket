<?php

namespace app\modules\users\roles;

use yii\web\AssetBundle;

/**
 * RolesAsset наследует основной пакет приложений для бэкэнд-приложений.
 * @see https://www.yiiframework.com/doc/api/2.0/yii-web-assetbundle
 */
class RolesAsset extends AssetBundle {

    /**
     *
     * @see https://www.yiiframework.com/doc/api/2.0/yii-web-assetbundle#$sourcePath-detail
     * @var string Содержит исходные файлы ресурсов для модуля
     */
    public $sourcePath = '@roles-assets';

    /**
     *
     * @see https://www.yiiframework.com/doc/api/2.0/yii-web-assetbundle#$css-detail
     * @var array Список css-файлов подключеных к модулю
     */
    public $css = [
    ];

    /**
     *
     * @see https://www.yiiframework.com/doc/api/2.0/yii-web-assetbundle#$js-detail
     * @var array Список js-файлов подключеных к модулю
     */
    public $js = [
        'js/roles.js',
        'js/permissions.js'
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
