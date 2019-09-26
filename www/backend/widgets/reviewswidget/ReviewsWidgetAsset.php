<?php

namespace backend\widgets\reviewswidget;

use yii\web\AssetBundle;

class ReviewsWidgetAsset extends AssetBundle {

    public $sourcePath = '@reviewswidget-assets';
    public $js = [
        'js/reviews.js',
    ];
    public $css = [
        'css/reviews.css',
    ];
    public $depends = [
        // 'yii\web\YiiAsset',
        // 'yii\bootstrap\BootstrapAsset',
    ];

}
