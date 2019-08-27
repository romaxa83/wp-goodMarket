<?php

namespace app\modules\settings;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class SettingsAsset extends AssetBundle
{
    public $sourcePath = '@settings-assets';
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
