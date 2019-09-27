<?php

namespace backend\modules\order\assets;

use yii\web\AssetBundle;

class OrderWidgetAsset extends AssetBundle
{
    public $sourcePath = '@orderwidget-assets';
    public $css = [
        'https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-css/1.4.6/select2-bootstrap.css',
        'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
        'css/order.css'
    ];
    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
        'js/order-widget.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    //public $jsOptions = ['position' => \yii\web\View::POS_END];
}