<?php

namespace backend\modules\blog\widgets\settings;

use yii\web\AssetBundle;

class SettingsWidgetAsset extends AssetBundle {

    public $sourcePath = '@settings-assets';
    public $js = [
        'js/settings.js',
    ];
    public $css = [
        'css/settings.css'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}
