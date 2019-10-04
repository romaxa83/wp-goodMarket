<?php
namespace frontend\controllers;

use yii\web\Controller;

class CategoryController extends Controller
{
    public function actionCatalog()
    {
        return 'catalog';
    }

    public function actionCategory(string $alias)
    {
        return 'page view category ' . $alias;
    }
}
