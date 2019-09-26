<?php

namespace backend\modules\import\widgets;

use yii\base\Widget;
use backend\modules\category\models\Category;
use yii\helpers\ArrayHelper;

class CategoryTreeWidget extends Widget {

    public $category;
    public $wrapper = false;
    public $count = 0;
    public $chooseCategory = false;

    public function init() {
        parent::init();
    }

    public function run() {
        if ($this->category != null) {
            $category = ArrayHelper::map(Category::find()->select(['category.id', 'category_lang.name as name'])->joinWith('categoryLang')->asArray()->all(), 'id', 'name');
            if ($this->wrapper) {
                $this->getCount($this->category);
                return $this->render('category-tree/index', [
                            'categoryXml' => $this->category,
                            'count' => $this->count,
                            'category' => $category,
                            'chooseCategory' => $this->chooseCategory
                ]);
            } else {
                return $this->render('category-tree/empty-wrapper', ['category' => $this->category]);
            }
        } else {
            return 'Не правильно переданы категории';
        }
    }

    private function getCount($category) {
        foreach ($category as $one) {
            $this->count++;
            if (isset($one['child'])) {
                $this->getCount($one['child']);
            }
        }
    }

}
