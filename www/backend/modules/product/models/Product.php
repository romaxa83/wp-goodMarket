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
    const SAVED_PRODUCT = 'save_product';

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
            /* Добавляемые продукты */
            [['alias', 'price', 'manufacturer_id', 'media_id', 'publish'], 'required', 'on' => self::ADDED_PRODUCT],
            [['alias'], 'unique', 'message' => 'Такой алиас уже существует', 'on' => self::ADDED_PRODUCT],
            ['alias', 'match', 'pattern' => '/^[a-z0-9_-]+$/', 'message' => 'Неверно введен алиас', 'on' => self::ADDED_PRODUCT],
            [['rating'], 'number', 'min' => 0, 'max' => 100, 'on' => self::ADDED_PRODUCT],
            /* Редактируемые продукты */
            [['alias', 'price', 'manufacturer_id', 'media_id', 'publish'], 'required', 'on' => self::SAVED_PRODUCT],
            ['alias', 'match', 'pattern' => '/^[a-z0-9_-]+$/', 'message' => 'Неверно введен алиас', 'on' => self::SAVED_PRODUCT],
            [['rating'], 'number', 'min' => 0, 'max' => 100, 'on' => self::SAVED_PRODUCT]
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
            'alias' => 'Алиас',
            'gallery' => 'Галерея',
            'amount' => 'Остаток на складе',
            'trade_price' => 'Оптовая цена',
            'stock_publish' => 'Статус на базе',
            'publish' => 'Опубликовать',
            'rating' => 'Рейтинг',
            'is_variant' => 'Вариативный товар',
            'type' => 'Тип',
            'vendor_code' => 'Артикул'
        ];
    }

    public function scenarios() {
        return [
            self::SCENARIO_DEFAULT => ['stock_id', 'category_id', 'media_id', 'manufacturer_id', 'group_id', 'alias', 'gallery', 'amount', 'trade_price', 'stock_publish', 'publish', 'rating', 'is_variant', 'type', 'vendor_code']
        ];
    }

    public function getSeo() {
        return $this->hasOne(SeoMeta::className(), ['id' => 'seo_id']);
    }

    public function getCategory() {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getCategoryLang() {
        return $this->hasOne(CategoryLang::className(), ['category_id' => 'category_id']);
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
        return $this->hasMany(VProduct::className(), ['product_id' => 'stock_id']);
    }

    public function getProductLang() {
        return $this->hasMany(ProductLang::className(), ['product_id' => 'id']);
    }

    // если в productLang price==null, то устанавливает price=trade_price товара
    public static function correctProductPriceAll(array $product_list) {
        foreach ($product_list as $k => $v) {
            foreach ($v['productLang'] as $k1 => $v1) {
                $price = $product_list[$k]['productLang'][$k1]['price'];
                $product_list[$k]['productLang'][$k1]['price'] = (is_null($price) ? $product_list[$k]['trade_price'] : $price);
            }
        }
        return $product_list;
    }

    public static function getProductsData(array $select, callable $callback, array $condition = []) {
        $productList = Product::find()->select($select)->with('productLang')->where(['publish' => TRUE]);
        if (!empty($condition)) {
            $productList->andWhere($condition);
        }
        $productList = $productList->asArray()->all();
        return $callback($productList);
    }

}
