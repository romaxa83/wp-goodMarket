<?php

namespace frontend\widgets\categorywidget;

use yii\web\AssetBundle;

class CategoryWidgetAssets extends AssetBundle 
{

    public $sourcePath = '@categorywidget-assets';
    public $js = [
        'js/category.js',
    ];
    public $css = [
        'css/category.css',
    ];
    public $depends = [

    ];

}
