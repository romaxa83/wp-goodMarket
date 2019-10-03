<?php

namespace backend\modules\banners\widgets\headerwidget;

use backend\modules\banners\models\Banner;
use backend\modules\banners\models\BannerLang;
use yii\base\Widget;

class BannerHeaderWidget extends Widget {

    private $header_resolutions = [];

    public function init() {
        parent::init();
        \Yii::setAlias('@banner-header-widget-assets', \Yii::getAlias('@backend') . '/modules/banners/widgets/headerwidget/assets');
        BannerHeaderWidgetAsset::register(\Yii::$app->view);
    }

    public function run() {
        $banners = Banner::find()->select(['id'])->where(['type' => Banner::BANNER_HEADER, 'status' => 1])->with([
            'bannerLang.media' => function ($query) {
                $query->select('id, url, alt');
            }])->limit(15)->asArray()->all();
        $rand = rand(0, count($banners) - 1);
        $banner = $banners[$rand];
        $url = $banner['bannerLang'][array_key_first($banner['bannerLang'])]['media']['url'];

        foreach (BannerLang::HEADER as $k => $v) {
            $path = explode('.', $url);
            $filename = $path[count($path) - 2] . "-$k";
            $path[count($path) - 2] = $filename;
            $this->header_resolutions[$k] = implode('.', $path);
        }
        $banner_alt = $banner['bannerLang'][array_key_first($banner['bannerLang'])]['media']['alt'];

        return $this->render('header', [
            'header_resolutions' => $this->header_resolutions,
            'banner_alt' => $banner_alt
        ]);
    }

}
