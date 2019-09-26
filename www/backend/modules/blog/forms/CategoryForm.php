<?php

namespace backend\modules\blog\forms;

use backend\modules\blog\entities\Category;
use backend\modules\blog\repository\CategoryRepository;
use backend\modules\blog\validators\AliasValidator;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class CategoryForm extends Model
{
    public $title;
    public $alias;

    public $parent_id;
    public $_category;

    public function __construct(Category $category = null,array $config = [])
    {
        if($category){
            $this->title = $category->title;
            $this->alias = $category->alias;
            $this->parent_id = $category->parent ? $category->parent->id : null;
            $this->_category = $category;
        }
        parent::__construct($config);
    }

    public function rules() {
        return [
            [['alias'], 'required'],
            [['alias'], 'string','max' => 250],
            [['parent_id'], 'integer'],
            ['alias', AliasValidator::class],
            ['alias', 'unique', 'targetClass' => Category::class, 'filter' => $this->_category ? ['<>', 'id', $this->_category->id] : null]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название категории',
            'alias' => 'Алиас категории',
            'parent_id' => 'Родительская категории',
        ];
    }

    public function categoriesList($for_post = null): array
    {
        $category = Category::find()->where(['status' => Category::STATUS_ACTIVE])->andWhere(['not',['id' => $this->_category->id ?? '']])->with('oneLang')->orderBy('lft')->asArray()->all();
        if($for_post){
            $category = Category::find()->where(['not',['id' => 1]])->andWhere(['status' => Category::STATUS_ACTIVE])->with('oneLang')->orderBy('lft')->asArray()->all();
        }
        return ArrayHelper::map($category, 'id', function (array $category) {
            $title = ($category['alias'] === 'root') ? 'Всё категорий' : $category['oneLang']['title'];
            
            return ($category['depth'] > 1 ? str_repeat('-- ', $category['depth'] - 1) . ' ' : '') . $title;
        });
    }
}
