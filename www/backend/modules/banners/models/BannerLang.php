<?php

namespace backend\modules\banners\models;

use yii\db\ActiveRecord;

class BannerLang extends ActiveRecord {

    public static function tableName() {
        return 'banner_lang';
    }

    public function rules() {
        return [
            [['text', 'title', 'alias'], 'string'],
            [['text', 'title', 'alias', 'media_id'], 'required', 'message' => 'Заполните поле'],
            [['publication'], 'integer'],
            ['image', 'image', 'extensions' => 'jpg, jpeg, gif, png', 'on' => ['insert', 'update']],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'banner_id' => 'ID баннера',
            'lang_id' => 'ID языка',
            'media_id' => 'ID файла',
            'alias' => 'Ссылка',
            'title' => 'Название',
            'text' => 'Текст'
        ];
    }

    public function getLang() {
        return $this->hasOne(Lang::className(), ['id' => 'lang_id']);
    }

}
