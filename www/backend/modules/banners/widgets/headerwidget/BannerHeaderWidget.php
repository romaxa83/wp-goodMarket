<?php

namespace backend\modules\banners\widgets\headerwidget;

use backend\modules\banners\models\BannerLang;
use yii\base\Widget;

class BannerHeaderWidget extends Widget {

    public $banners = [];

    public function init() {
        parent::init();
        \Yii::setAlias('@banner-header-widget-assets', \Yii::getAlias('@backend') . '/modules/banners/widgets/headerwidget/assets');
        BannerHeaderWidgetAsset::register(\Yii::$app->view);
    }

    public function run() {
        $rand = rand(0, count($this->banners) - 1);
        $banner = $this->banners[$rand];

        foreach ($banner['bannerLang'] as $lang_key => $lang_value) {
            if (isset($lang_value['media']['url'])) {
                $url = $lang_value['media']['url'];

                foreach (BannerLang::HEADER as $k => $v) {
                    $path = explode('.', $url);
                    $filename = $path[count($path) - 2] . "-$k";
                    $path[count($path) - 2] = $filename;
                    $header_resolutions[$k] = implode('.', $path);
                }

                $banner['bannerLang'][$lang_key]['media']['header_resolutions'] = $header_resolutions;

            } else continue;
        }

        return $this->render('header', [
            'banner' => $banner,
        ]);
    }

}
