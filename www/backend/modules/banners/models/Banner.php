<?php

namespace backend\modules\banners\models;

use backend\modules\banners\models\BannerLang;
use yii\db\ActiveRecord;

class Banner extends ActiveRecord {

    public $languageData;

    public static function tableName() {
        return 'banner';
    }

    public function rules() {
        return [
            [['status'], 'required']
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'position' => 'Позиция',
            'status' => 'Опубликовать'
        ];
    }

    public function scenarios() {
        return [
            self::SCENARIO_DEFAULT => ['status']
        ];
    }

    public function getBannerLang() {
        return $this->hasMany(BannerLang::className(), ['banner_id' => 'id']);
    }

}
