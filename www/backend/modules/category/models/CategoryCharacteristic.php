<?php

namespace backend\modules\category\models;

/**
 * @property int $id
 * @property int $category_id
 * @property int $characteristic_id
 */
class CategoryCharacteristic extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'category_characteristic';
    }

    public function rules() {
        return [
            [['category_id', 'characteristic_id'], 'required'],
            [['category_id', 'characteristic_id'], 'integer']
        ];
    }

     //Relation
}
