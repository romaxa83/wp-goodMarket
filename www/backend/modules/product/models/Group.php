<?php

namespace backend\modules\product\models;

use Yii;
use yii\db\ActiveRecord;

class Group extends ActiveRecord {

    public $languageData;

    public static function tableName() {
        return 'group';
    }

    public function rules() {
        return [
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'status' => 'Статус'
        ];
    }

}
