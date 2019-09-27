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
use backend\widgets\langwidget\LangWidget;

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
        $langModel = new CategoryLang();
        
        $post = Yii::$app->request->post();

        if(Yii::$app->request->isPost){
            if($form->load($post) && $form->validate() && LangWidget::validate($langModel,$post)){
                try {
                    $category = $this->category_service->create($form);
                    $langModel->saveLang($post['CategoryLang'],$category->id);
    
                    Yii::$app->session->setFlash('success', 'Категория создана');
                    return $this->redirect(['update?id=' . $category->id]);
                } catch (\DomainException $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }else{
                $langModel->languageData = $post['CategoryLang'];
            }
        }

        return $this->render('create', [
            'model' => $form,
            'langModel' => $langModel
        ]);
    }

    public function actionUpdate($id)
    {
        $form = new CategoryForm($this->findModel($id));
        $langModel = new CategoryLang();

        foreach ($form->_category['manyLang'] as $indexRow => $oneLang) {
            $langAlias = $form->_category['aliasLang'][$indexRow]->alias;
            $langModel->languageData[$langAlias]['title'] = $oneLang->title;
        }
        $post = Yii::$app->request->post();

        if(Yii::$app->request->isPost){
            if ($form->load($post) && $form->validate() && LangWidget::validate($langModel,$post)) {
                try {
                    $category = $this->category_service->edit($id, $form);
                    $langModel->updateLang($post['CategoryLang'],$id);

                    Yii::$app->session->setFlash('success', 'Категория обновлена');
                    return $this->redirect(['update?id=' . $id]);
                } catch (\DomainException $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }else{
                $langModel->languageData = $post['CategoryLang'];
            }
        }

        return $this->render('update', [
            'model' => $form,
            'langModel' => $langModel
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
        if (($model = Category::find()->where(['id' => $id])->with(['manyLang','aliasLang'])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}