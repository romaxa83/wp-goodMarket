<?php
namespace frontend\controllers;

use yii\web\Controller;

class BlogController extends Controller
{
    public function actionBlog()
    {
        return 'blog';
    }

    public function actionArticle(string $alias)
    {
        return 'page view actionArticle ' . $alias;
    }
}
