<?php

namespace app\modules\reviews;

use yii\web\AssetBundle;

/**
 * BlogAsset наследует основной пакет приложений для бэкэнд-приложений.
 * @see https://www.yiiframework.com/doc/api/2.0/yii-web-assetbundle
 */
class ReviewsAsset extends AssetBundle {
    
    public $sourcePath = '@reviews-assets';
    
    public $css = [
        'css/styles.css'
    ];
    
    public $js = [
        'js/main.js'
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}
