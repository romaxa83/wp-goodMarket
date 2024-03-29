<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle {

    public $jsOptions = ['position' => View::POS_BEGIN];
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/jquery-ui.css',
        'css/icheck/red.css',
        'css/site.css',
    ];
    public $js = [
        'js/jquery-ui.js',
        'js/icheck.js',
        'js/main.js',
        '//cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.15/lodash.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}
