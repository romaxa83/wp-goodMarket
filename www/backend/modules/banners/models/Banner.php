<?php

namespace backend\modules\banners\models;

use backend\modules\banners\models\BannerLang;
use yii\db\ActiveRecord;

class Banner extends ActiveRecord {

    const BANNER_SLIDER = 'slider';
    const BANNER_HEADER = 'header';
    const BANNER_TYPES = ['slider' => 'Слайдер', 'header' => 'Хедер'];
    public $languageData;

    public static function tableName() {
        return 'banner';
    }

    public function rules() {
        return [
            [['status', 'type'], 'required'],
            [['status'], 'number'],
            [['type'], 'string']
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'position' => 'Позиция',
            'type' => 'Тип',
            'status' => 'Опубликовать'
        ];
    }

    public function scenarios() {
        return [
            self::SCENARIO_DEFAULT => ['status', 'type']
        ];
    }

    public function getBannerLang() {
        return $this->hasMany(BannerLang::className(), ['banner_id' => 'id']);
    }

}
