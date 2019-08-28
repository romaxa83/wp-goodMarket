<?php

namespace backend\modules\product\models;

use Yii;
use yii\db\ActiveRecord;

class ProductCharacteristic extends ActiveRecord {

    public $languageData;

    public static function tableName() {
        return 'product_characteristic';
    }

    public function rules() {
        return [];
    }

    public function scenarios() {
        return [
            self::SCENARIO_DEFAULT => ['product_id', 'group_id', 'characteristic_id', 'value']
        ];
    }

    public function attributeLabels() {
        return [
            'value' => 'Значение'
        ];
    }

}
