<?php

namespace backend\modules\product\models;

use backend\modules\category\models\Category;
use backend\modules\filemanager\models\Mediafile;
use backend\modules\seo\models\SeoMeta;
use backend\modules\product\models\ProductLang;
use backend\modules\category\models\CategoryLang;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Product extends ActiveRecord {

    const ADDED_PRODUCT = 'added_product';

    public $languageData;
    public $product_data;

    /**
     * @see https://www.yiiframework.com/doc/api/2.0/yii-db-activerecord#tableName()-detail
     */
    public static function tableName() {
        return 'product';
    }

    /**
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-model#rules()-detail
     * @return array
     */
    public function rules() {
        return [
            [['category_id', 'manufacturer_id', 'amount', 'rating', 'publish'], 'required'],
            [['rating'], 'number', 'min' => 0, 'max' => 100],
        ];
    }

    /**
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-model#attributeLabels()-detail
     * @return array
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'stock_id' => 'ID',
            'category_id' => 'Категория',
            'media_id' => 'Изображение',
            'manufacturer_id' => 'Производитель',
            'group_id' => 'Группа',
            'gallery' => 'Галерея',
            'amount' => 'Количество',
            'trade_price' => 'Оптовая цена',
            'stock_publish' => 'Статус на базе',
            'publish' => 'Опубликовать',
            'rating' => 'Рейтинг',
            'is_variant' => 'Вариативный товар',
            'type' => 'Тип',
            'vendor_code' => 'Артикул',
            'new' => 'Отображать ли статус новый'
        ];
    }

    public function scenarios() {
        return [
            self::SCENARIO_DEFAULT => ['stock_id', 'category_id', 'media_id', 'manufacturer_id', 'group_id', 'gallery', 'amount', 'trade_price', 'stock_publish', 'publish', 'rating', 'is_variant', 'type', 'vendor_code']
        ];
    }

    public function getSeo() {
        return $this->hasOne(SeoMeta::className(), ['id' => 'seo_id']);
    }

    public function getCategory() {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getCategoryLang() {
        return $this->hasMany(CategoryLang::className(), ['category_id' => 'category_id']);
    }

    public function getMedia() {
        return $this->hasOne(Mediafile::className(), ['id' => 'media_id']);
    }

    public function getGroup() {
        return $this->hasOne(Group::className(), ['id' => 'group_id']);
    }

    public function getManufacturer() {
        return $this->hasOne(Manufacturer::className(), ['id' => 'manufacturer_id']);
    }

    public function getVproducts() {
        return $this->hasMany(VProduct::className(), ['product_id' => 'id']);
    }

    public function getProductLang() {
        return $this->hasMany(ProductLang::className(), ['product_id' => 'id']);
    }

    public static function getProductsByCategory($category_id) {
        $product_list = Product::find()->where(['publish' => 1, 'category_id' => $category_id])->all();
        return $product_list;
    }

    public static function getProduct($product_id, $category_id) {
        $product_list = Product::getProductsByCategory($category_id);
        if (!array_key_exists($product_id, $product_list)) {
            return [];
        }
        return $product_list[$product_id];
    }

}
