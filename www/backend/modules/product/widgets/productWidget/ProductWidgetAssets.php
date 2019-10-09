<?php

namespace backend\modules\product\widgets\productWidget;

use yii\web\AssetBundle;

class ProductWidgetAssets extends AssetBundle 
{

    public $sourcePath = '@productwidget-assets';
    public $js = [
        'js/product.js',
    ];
    public $css = [
        'css/product.css',
    ];
    public $depends = [

    ];

}
