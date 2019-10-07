<?php
namespace frontend\controllers;

use yii\web\Controller;

class ProductController extends Controller
{
    public function actionProduct(string $alias)
    {
        return $this->render('index');
    }
}