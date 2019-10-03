<?php

namespace backend\modules\banners\widgets\sliderwidget;

use yii\base\Widget;

class BannerSliderWidget extends Widget {

    public $attribute = [];
    public $hide_col = [];
    public $model;

    public function init() {
        parent::init();
        \Yii::setAlias('@banner-slider-widget-assets', \Yii::getAlias('@backend') . '/modules/banners/widgets/sliderwidget/assets');
        BannerSliderWidgetAsset::register(\Yii::$app->view);
    }

    public function run() {
        return $this->render('hide-col', [
                    'attributes' => $this->attribute,
                    'model' => $this->model,
                    'hide_col' => $this->hide_col
        ]);
    }
}
