<?php

namespace backend\modules\content\widgets\select_template_widget;


use yii\web\AssetBundle;

class SelectTemplateWidgetAsset extends AssetBundle
{
    public $sourcePath = '@select-template-assets';

    public $js = [
        'js/select-template.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}