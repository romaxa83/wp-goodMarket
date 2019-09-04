<?php

namespace backend\modules\banners\models;

use yii\db\ActiveRecord;
use common\models\Lang;
use backend\modules\filemanager\models\Mediafile;
use backend\widgets\langwidget\LangWidget;

class BannerLang extends ActiveRecord {

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

}
