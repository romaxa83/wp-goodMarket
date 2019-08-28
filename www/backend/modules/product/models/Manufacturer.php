<?php

namespace backend\modules\product\models;

use Yii;
use yii\db\ActiveRecord;

class Manufacturer extends ActiveRecord {

    public $languageData;

    /**
     * @see https://www.yiiframework.com/doc/api/2.0/yii-db-activerecord#tableName()-detail
     */
    public static function tableName() {
        return 'manufacturer';
    }

    /**
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-model#rules()-detail
     * @return array
     */
    public function rules() {
        return [
            [['name','slug'], 'required'],
        ];
    }

    /**
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-model#attributeLabels()-detail
     * @return array
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'slug' => 'Slug'
        ];
    }

    public function getProducts()
    {
        return $this->hasMany(Product::className(),['manufacturer_id' => 'id']);
    }

}
