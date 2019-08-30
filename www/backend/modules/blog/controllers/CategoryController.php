<?php

namespace backend\modules\blog\controllers;

use Yii;
use yii\base\Module;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use backend\modules\blog\entities\Category;
use backend\modules\blog\forms\CategoryForm;
use backend\modules\blog\helpers\StatusHelper;
use backend\modules\blog\services\CategoryService;
use backend\modules\blog\forms\search\CategorySearch;

class CategoryController extends Controller
{
    private $category_service;

    public function __construct($id, Module $module,CategoryService $category_service, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->category_service = $category_service;
    }
 
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'category' => $this->findModel($id)
        ]);
    }

    public function actionCreate()
    {
        $form = new CategoryForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {

            try {
                $this->category_service->create($form);

                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }

        }
        return $this->render('create', [
            'model' => $form,
        ]);
    }

    public function actionUpdate($id)
    {
        $category = $this->findModel($id);
        $form = new CategoryForm($category);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->category_service->edit($category->id, $form);
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'category' => $category,
        ]);
    }

    public function actionStatusChange()
    {
        $post = Yii::$app->request->post();
        try{
            $this->category_service->changeStatus($post['id'],$post['checked']);
            Yii::$app->session->setFlash('success',StatusHelper::infoFlash($post['checked'],'Категория'));

            return $this->redirect(Url::toRoute('/blog/category/index'));
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }

    public function actionDelete($id)
    {
        try {
            $this->category_service->remove($id);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }

    public function actionMoveUp($id)
    {
        $this->category_service->moveUp($id);
        return $this->redirect(['index']);
    }

    public function actionMoveDown($id)
    {
        $this->category_service->moveDown($id);
        return $this->redirect(['index']);
    }

    protected function findModel($id) : Category
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}