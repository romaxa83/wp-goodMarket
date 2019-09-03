<?php

namespace backend\modules\banners\models;

use yii\db\ActiveRecord;
use common\models\Lang;
use backend\modules\filemanager\models\Mediafile;

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

}
