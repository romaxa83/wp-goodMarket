<?php

namespace backend\modules\banners\widgets\sliderwidget;

use backend\modules\banners\models\BannerLang;
use yii\base\Widget;

class BannerSliderWidget extends Widget {

    public $banners = [];

    public function init() {
        parent::init();
        \Yii::setAlias('@banner-slider-widget-assets', \Yii::getAlias('@backend') . '/modules/banners/widgets/sliderwidget/assets');
        BannerSliderWidgetAsset::register(\Yii::$app->view);
    }

    public function run() {
        if (!empty($this->banners)) {
            foreach ($this->banners as $banner_key => $banner) {
                $header_resolutions = [];

                foreach ($banner['bannerLang'] as $lang_key => $lang_value) {
                    if (isset($lang_value['media']['url'])) {
                        $url = $lang_value['media']['url'];

                        foreach (BannerLang::SLIDER as $k => $v) {
                            $path = explode('.', $url);
                            $filename = $path[count($path) - 2] . "-$k";
                            $path[count($path) - 2] = $filename;
                            $header_resolutions[$k] = implode('.', $path);
                        }

                        $this->banners[$banner_key]['bannerLang'][$lang_key]['media']['header_resolutions'] = $header_resolutions;

                    } else continue;
                }
            }
        }

        return $this->render('slider', [
            'banners' => $this->banners
        ]);
    }
}
