<?php


namespace common\models;


use yii\db\ActiveRecord;

class Lang extends ActiveRecord  {
    public static function tableName() {
        return 'lang';
    }
}
