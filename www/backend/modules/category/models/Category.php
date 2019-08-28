<?php

namespace backend\modules\category\models;

use backend\modules\category\models\CategoryLang;
use backend\modules\product\models\Product;
use backend\modules\seo\models\SeoMeta;
use backend\modules\filemanager\models\Mediafile;
use yii\helpers\ArrayHelper;

/**
 * Это класс модели для таблицы "pages".
 *
 * @property int $id
 * @property string $name
 * @property int $position
 * @property string $alias
 * @property string $type
 * @property int $status
 */
class Category extends \yii\db\ActiveRecord {
    const ADDED_CATEGORY = 'added_product';
    const SAVED_CATEGORY = 'save_product';
    public $languageData;

    /**
     * @see https://www.yiiframework.com/doc/api/2.0/yii-db-activerecord#tableName()-detail
     */
    public static function tableName() {
        return 'category';
    }

    /**
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-model#rules()-detail
     * @return array
     */
    public function rules() {
        return [
            [['alias', 'rating', 'media_id', 'publish'], 'required', 'message' => 'Необходимо заполнить' ,'on'=>self::ADDED_CATEGORY],
            [['parent_id', 'rating', 'publish', 'media_id'], 'number', 'on'=>self::ADDED_CATEGORY],
            ['alias', 'unique', 'message'=>'Такой алиас уже существует', 'on'=>self::ADDED_CATEGORY],
            ['alias', 'match', 'pattern' => '/^[a-z0-9_-]+$/', 'message' => 'Неверно введен алиас', 'on'=>self::ADDED_CATEGORY],

            [['alias', 'rating', 'media_id', 'publish'], 'required', 'on'=>self::SAVED_CATEGORY],
            [['parent_id', 'rating', 'publish', 'media_id'], 'number', 'on'=>self::SAVED_CATEGORY],
            ['alias', 'match', 'pattern' => '/^[a-z0-9_-]+$/', 'message' => 'Неверно введен алиас', 'on'=>self::SAVED_CATEGORY]
        ];
    }

    /**
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-model#attributeLabels()-detail
     * @return array
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'alias' => 'Алиас',
            'position' => 'Позиция',
            'rating' => 'Рейтинг',
            'publish' => 'Опубликовать',
            'media_id' => 'Медиа'
        ];
    }

    /**
     * событие для вытягивание сео данных
     */
    public function getSeo()
    {
        return $this->hasOne(SeoMeta::className(), ['id' => 'seo_id']);
    }

    public function getProducts()
    {
        return $this->hasMany(Product::className(),['category_id' => 'stock_id']);
    }

    public function getMedia()
    {
        return $this->hasMany(Mediafile::className(), ['id' => 'media_id']);
    }

    public function getCategoryLang() {
        return $this->hasMany(CategoryLang::className(), ['category_id' => 'id']);
    }

    public function getSelect2List() {
        $categoryList = Category::find()->select(['id', 'alias'])->with('categoryLang')->where(['publish' => TRUE])->asArray()->all();
        $categoryList = ArrayHelper::map($categoryList, 'id',  function ($element) {
            return isset($element['categoryLang'][0]['name']) ? $element['categoryLang'][0]['name'] : $element['alias'];
        });
        unset($categoryList[$this->id]);
        return $categoryList;
    }

}
