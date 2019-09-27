<?php

namespace backend\modules\content\widgets\add_block_widget;

use yii\web\AssetBundle;

class AddBlockWidgetAssets extends AssetBundle
{
    public $sourcePath = '@add-block-assets';
    public $js = [
        'js/add-block.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}