<?php

namespace backend\widgets\hide_col;

use yii\web\AssetBundle;

class HideColWidgetAsset extends AssetBundle {

    public $sourcePath = '@hide-col-assets';
    public $js = [
        'js/hide-col.js',
    ];
    public $css = [
        'css/hide-col.css'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}