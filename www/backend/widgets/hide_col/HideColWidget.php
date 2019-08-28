<?php

namespace backend\widgets\hide_col;

use yii\base\Widget;

class HideColWidget extends Widget {

    public $attribute = [];
    public $hide_col = [];
    public $model;

    public function init() {
        parent::init();

        \Yii::setAlias('@hide-col-assets', \Yii::getAlias('@backend') . '/widgets/hide_col/assets');

        HideColWidgetAsset::register(\Yii::$app->view);
    }

    public function run() {
        return $this->render('hide-col', [
                    'attributes' => $this->attribute,
                    'model' => $this->model,
                    'hide_col' => $this->hide_col
        ]);
    }

    public static function setConfig($attribute, $arr_check, $another_config = null) {
        $config = [
            'data-attr' => $attribute,
            'style' => $arr_check !== null && in_array($attribute, $arr_check) ? 'display:none' : ''
        ];
        if ($another_config) {
            $config = array_merge($config, $another_config);
        }
        return $config;
    }

}
