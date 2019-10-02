<?php

namespace backend\modules\content\widgets\sync_alias_widget;

use yii\web\AssetBundle;

class SyncAliasWidgetAssets extends AssetBundle {

    public $sourcePath = '@sync-alias-assets';
    public $css = [
        'css/sync_alias.css'
    ];
    public $js = [
        'js/sync_alias.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}
