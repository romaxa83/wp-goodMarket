<?php

namespace frontend\widgets\langWidget;

use yii\web\AssetBundle;

class LangWidgetAssets extends AssetBundle 
{

    public $sourcePath = '@langwidget-assets';
    public $js = [
        'js/lang.js',
    ];
    public $css = [
        'css/lang.css',
    ];
    public $depends = [

    ];

}
