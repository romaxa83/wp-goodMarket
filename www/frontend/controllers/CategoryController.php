<?php
namespace frontend\controllers;

use yii\web\Controller;

class CategoryController extends Controller
{
    public function actionCatalog()
    {
        return $this->render('index');
    }

    public function actionCategory(string $alias)
    {
        return $this->render('category');
    }
}
