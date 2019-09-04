<?php

namespace backend\modules\blog\repository;

use backend\modules\blog\entities\Category;

class CategoryRepository
{
    public function get($id): Category
    {
        if (!$category = Category::findOne($id)) {
            throw new \DomainException('Category is not found.');
        }
        return $category;
    }

    public function getAll()
    {
        if (!$category = Category::find()
            ->where(['status' => Category::STATUS_ACTIVE])
            ->andWhere(['>', 'depth', 0])
            ->andWhere(['not in','alias','root'])->orderBy(['lft' => SORT_ASC])
            ->all()) {
            return false;
        }
        return $category;
    }

    /**
     * получение категории по алиасу
     * @param $alias
     * @return Category|null
     */
    public function findByAlias($alias): ? Category
    {
        return Category::findOne(['alias' => $alias]);
    }

    public function findByName($title): ? Category
    {
        return Category::findOne(['title' => $title]);
    }

    public function save(Category $category): void
    {
        if (!$category->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function remove(Category $category): void
    {
        if (!$category->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}
