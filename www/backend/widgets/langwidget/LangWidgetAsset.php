<?php

namespace backend\widgets\langwidget;

use yii\web\AssetBundle;

class LangWidgetAsset extends AssetBundle {

    public $sourcePath = '@langwidget-assets';
    public $css = [
        'css/langwidget.css'
    ];
    public $js = [
        'js/langwidget.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}
