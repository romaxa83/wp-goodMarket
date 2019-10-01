<?php

namespace backend\modules\product\models;

use Yii;
use yii\db\ActiveRecord;
use backend\modules\product\models\Characteristic;
use backend\modules\product\models\Group;

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

    public function getCharacteristic() {
        return $this->hasOne(Characteristic::className(), ['id' => 'characteristic_id']);
    }

    public function getGroup() {
        return $this->hasOne(Group::className(), ['id' => 'group_id']);
    }

    public static function CombinationOfCharacteristics($arr) {
        $result = array();
        $total = count($arr);
        while(true) {
            $row = array();
            foreach ($arr as $key => $value) {
                $row[] = current($value);
            }
            $result[] = $row;
            for ($i = $total - 1; $i >= 0; $i--) {
                if (next($arr[$i])) {
                    break;
                }
                elseif ($i == 0) {
                    break 2;
                }
                else {
                    reset($arr[$i]);
                }
            }
        }
        return $result;
    }

}
