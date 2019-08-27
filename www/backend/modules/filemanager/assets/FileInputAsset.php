<?php

namespace backend\modules\filemanager\assets;

use yii\web\AssetBundle;

class FileInputAsset extends AssetBundle
{
    public $sourcePath = '@filemanager/assets/source';

    public $js = [
        'js/fileinput.js',
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
        'backend\modules\filemanager\assets\ModalAsset',
    ];
}
