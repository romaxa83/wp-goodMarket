<?php

namespace backend\modules\product\models;

use yii\db\ActiveRecord;
use backend\models\Lang;

class ProductLang extends ActiveRecord {

    /**
     * @see https://www.yiiframework.com/doc/api/2.0/yii-db-activerecord#tableName()-detail
     */
    public static function tableName() {
        return 'product_lang';
    }

    /**
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-model#rules()-detail
     * @return array
     */
    public function rules() {
        return [
            [['product_id', 'lang_id', 'name', 'description', 'price', 'currency'], 'required'],
        ];
    }

    /**
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-model#attributeLabels()-detail
     * @return array
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'product_id' => 'Продукт',
            'lang_id' => 'Язык',
            'name' => 'Название',
            'description' => 'Описание',
            'price' => 'Цена',
            'currency' => 'Валюта'
        ];
    }

    public function getLang() {
        return $this->hasOne(Lang::className(), ['id' => 'lang_id']);
    }

    public function scenarios() {
        return [
            self::SCENARIO_DEFAULT => ['name', 'description', 'price']
        ];
    }

}
