<?php

namespace backend\modules\blog\controllers;

use Yii;
use yii\base\Module;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use backend\modules\blog\entities\Category;
use backend\modules\blog\entities\CategoryLang;
use backend\modules\blog\forms\CategoryForm;
use backend\modules\blog\helpers\StatusHelper;
use backend\modules\blog\services\CategoryService;
use backend\modules\blog\forms\search\CategorySearch;

class CategoryController extends Controller
{
    private $category_service;
    private $category_lang;

    public function __construct($id, Module $module,CategoryService $category_service, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->category_service = $category_service;
        $this->category_lang = new CategoryLang();
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
        $post = Yii::$app->request->post();

        if($form->load($post) && $form->validate()){
            try {
                $form->title = $post['CategoryForm']['Language']['ru'];
                $category = $this->category_service->create($form);
                $resultat = $this->category_lang->saveLang($post['CategoryForm']['Language'],$category->id);

                return $this->redirect(['update?id='.$category->id]);
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
        //get base model
        $category = $this->findModel($id);
        //get by related model with lang
        $relatedRecord = $category->getRelatedRecords();
        $form = new CategoryForm($category);
        //cycle for assembly languageData
        $data['Language'] = [];
        foreach ($relatedRecord as $oneLangModel) {
            foreach ($oneLangModel as $oneLang) {
                //get by related alias lang
                $langSetting = $oneLang->getLang()->select('alias')->one();
                $data['Language'][$langSetting->alias]['title'] = $oneLang->title;
            }
        }
        $form->languageData = $data;
        $post = Yii::$app->request->post();

        if ($form->load($post) && $form->validate()) {
            try {
                $form->title = $post['CategoryForm']['Language']['ru'];
                $this->category_service->edit($category->id, $form);
                $resultat = $this->category_lang->updateLang($post['CategoryForm']['Language'],$category->id);

                return $this->redirect(['update?id='.$category->id]);
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
        if (($model = Category::find()->where(['id' => $id])->with('title')->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}