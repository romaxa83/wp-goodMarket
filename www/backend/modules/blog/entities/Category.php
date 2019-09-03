<?php

namespace backend\modules\blog\entities;

use yii\db\ActiveRecord;
use paulzi\nestedsets\NestedSetsBehavior;
use backend\modules\blog\forms\queries\CategoryQuery;
use backend\modules\blog\entities\CategoryLang;

/**
 * @property integer $id
 * @property string $title
 * @property string $alias
 * @property int $status
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 * @property int $created_at
 * @property int $updated_at
 *
 * @mixin NestedSetsBehavior
 */

class Category extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    public $title;

    public static function tableName()
    {
        return '{{%blog_category}}';
    }

    public static function create($title,$alias) : self
    {
        $category = new static();
        $category->title = $title;
        $category->alias = $alias;
        $category->created_at = time();
        $category->updated_at = time();

        return $category;
    }

    public function edit($title,$alias):void
    {
        $this->title = $title;
        $this->alias = $alias;
        $this->updated_at = time();
    }

    public function status($status):void
    {
        $this->status = $status;
    }

    public function behaviors(): array
    {
        return [
            NestedSetsBehavior::className(),
        ];
    }

    // транзакция для работы NestedSets
    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    //переопределяем ActiveQuery для работы с NestedSets
    public static function find() : CategoryQuery
    {
        return new CategoryQuery(static::class);
    }

    public function getLangRow(int $lang_id = 1)
    {   
        return $this->hasOne(CategoryLang::class, ['category_id' => 'id'])->andWhere(['lang_id' => $lang_id]);
    }

    public function getTitle(int $lang_id = 1)
    {   
        return $this->hasMany(CategoryLang::class, ['category_id' => 'id']);
    }
}