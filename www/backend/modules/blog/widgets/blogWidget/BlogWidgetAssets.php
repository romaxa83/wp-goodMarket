<?php

namespace backend\modules\blog\widgets\blogWidget;

use yii\web\AssetBundle;

class BlogWidgetAssets extends AssetBundle 
{

    public $sourcePath = '@blogwidget-assets';
    public $js = [
        'js/blog.js',
    ];
    public $css = [
        'css/blog.css',
    ];
    public $depends = [

    ];

}
