<?php

namespace backend\modules\import;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Класс MenuAsset наследует основной класс AssetBundle для работы с ресурсами проекта.
 * @see https://www.yiiframework.com/doc/api/2.0/yii-web-assetbundle
 */
class ImportAssets extends AssetBundle {

    /**
     * @see https://www.yiiframework.com/doc/api/2.0/yii-web-assetbundle#$sourcePath-detail
     * @var string Содержит исходные файлы ресурсов для модуля.
     */
    public $sourcePath = '@import-assets';

    /**
     * @see https://www.yiiframework.com/doc/api/2.0/yii-web-assetbundle#$css-detail
     * @var array Список css-файлов подключеных к модулю.
     */
    public $css = [
        'css/import.css'
    ];

    /**
     * @see https://www.yiiframework.com/doc/api/2.0/yii-web-assetbundle#$js-detail
     * @var array Список js-файлов подключеных к модулю.
     */
    public $js = [
        'js/import.js'
    ];

}
