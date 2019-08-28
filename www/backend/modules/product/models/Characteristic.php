<?php

namespace backend\modules\product\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class Characteristic extends ActiveRecord {

    public $languageData;

    public static function tableName() {
        return 'characteristic';
    }

    public function rules() {
        return [
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'type' => 'Тип',
            'status' => 'Статус'
        ];
    }

    //Relation
    public function getValues(): ActiveQuery {
        return $this->hasMany(ProductCharacteristic::class, ['characteristic_id' => 'id']);
    }

}
