<?php

namespace backend\modules\banners\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\Lang;
use backend\modules\filemanager\models\Mediafile;
use backend\widgets\langwidget\LangWidget;

class BannerLang extends ActiveRecord {

    const HEADER = [
        'xxl' => ['width' => 1920, 'height' => 30],
        'xl' => ['width' => 1366, 'height' => 30],
        'md' => ['width' => 960, 'height' => 30],
        'sm' => ['width' => 414, 'height' => 30]
    ];
    const SLIDER = [
        'xxl' => ['width' => 1170, 'height' => 370],
        'xl' => ['width' => 870, 'height' => 370],
        'md' => ['width' => 690, 'height' => 334],
        'sm' => ['width' => 414, 'height' => 370]
    ];
    public $languageData;

    public static function tableName() {
        return 'banner_lang';
    }

    public function rules() {
        return [
            [['media_id', 'alias', 'title', 'text'], 'required', 'message' => 'Поле не может быть пустым:']
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'banner_id' => 'ID баннера',
            'lang_id' => 'ID языка',
            'media_id' => 'Баннер',
            'alias' => 'Ссылка',
            'title' => 'Название',
            'text' => 'Текст'
        ];
    }

    public function getMedia() {
        return $this->hasOne(Mediafile::className(), ['id' => 'media_id']);
    }

    public function getLang() {
        return $this->hasOne(Lang::className(), ['id' => 'lang_id']);
    }

    public function getBanner() {
        return $this->hasOne(Banner::className(), ['id' => 'banner_id']);
    }

    public static function saveAll($model, $modelLang, $data = []) {
        $success = FALSE;
        $modelLang->languageData = $data['BannerLang'];
        $LW = LangWidget::getActiveLanguageData(['id', 'alias']);
        $model->attributes = $data['Banner'];
        if ($model->validate() && LangWidget::validate($modelLang, $data)) {
            $model->save();
            foreach ($LW as $item) {
                $lang = BannerLang::find()->where(['banner_id' => $modelLang->banner_id, 'lang_id' => $item['id']])->one();
                if ($lang === NULL) {
                    $lang = new BannerLang();
                }
                $lang->attributes = $data['BannerLang'][$item['alias']];
                $lang->banner_id = $model->id;
                $lang->lang_id = $item['id'];
                if ($lang->validate()) {
                    $lang->save();
                }
            }
            $success = TRUE;
        }
        return $success;
    }

    public static function cropBanner(int $banner_id) {
        $banner = Banner::find()->where(['id' => $banner_id])->with('bannerLang.media')->one();

        if (isset($banner['bannerLang']) && !empty($banner['bannerLang'])) {
            $banner_type = strtoupper($banner['type']);

            foreach ($banner['bannerLang'] as $k => $v) {
                if (isset($v['media']) && !empty($v['media'])) {
                    foreach (constant("self::{$banner_type}") as $mk => $mv) {
                        Mediafile::bannerCropping(Yii::getAlias("@webroot" . $v['media']['url']), $mv['width'], $mv['height'], $mk);
                    }
                }
            }
        }
    }

    public function afterDelete() {
        $this->purgeBannerFiles();
        return parent::afterDelete();
    }

    public function purgeBannerFiles() {
        $filePath = Yii::getAlias("@webroot" . Mediafile::findOne($this->media_id)->url);
        $banner_type = strtoupper($this->banner->type);
        foreach (constant("self::{$banner_type}") as $mk => $mv) {
            $path = explode('.', $filePath);
            $filename = $path[count($path)-2] . "-$mk";
            $path[count($path)-2] = $filename;
            $newPath = implode('.', $path);
            if (file_exists($newPath)) {
                unlink($newPath);
            }
        }
    }
}
