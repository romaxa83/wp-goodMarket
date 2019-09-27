<?php

namespace backend\modules\blog\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use backend\modules\blog\entities\Tag;
use backend\modules\blog\forms\TagForm;
use backend\modules\blog\services\TagService;
use backend\modules\blog\helpers\StatusHelper;
use backend\modules\blog\forms\search\TagSearch;
use backend\modules\blog\entities\TagLang;
use backend\widgets\langwidget\LangWidget;

class TagController extends Controller
{
    /**
     * @var TagService
     */
    private $tag_service;
    private $tag_lang;

    public function __construct($id, $module, TagService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->tag_service = $service;
    }

    public function actionIndex()
    {
        $searchModel = new TagSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'tag' => $this->findModel($id)
        ]);
    }

    public function actionCreate()
    {
        $form = new TagForm();
        $langModel = new TagLang();

        $post = Yii::$app->request->post();

        if(Yii::$app->request->isPost){
            if ($form->load($post) && $form->validate() && LangWidget::validate($langModel,$post)) {
                try {
                    $tag = $this->tag_service->create($form);
                    $langModel->saveLang($post['TagLang'],$tag->id);

                    Yii::$app->session->setFlash('success', 'Тег создан');
                    return $this->redirect(['update?id=' . $tag->id]);
                } catch (\DomainException $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }else{
                $langModel->languageData = $post['TagLang'];
            }
        }

        return $this->render('create', [
            'model' => $form,
            'langModel' => $langModel
        ]);
    }

    public function actionUpdate($id)
    {
        $form = new TagForm($this->findModel($id));
        $langModel = new TagLang();

        foreach ($form->_tag['manyLang'] as $indexRow => $oneLang) {
            $langAlias = $form->_tag['aliasLang'][$indexRow]->alias;
            $langModel->languageData[$langAlias]['title'] = $oneLang->title;
        }
        $post = Yii::$app->request->post();

        if(Yii::$app->request->isPost){
            if ($form->load($post) && $form->validate() && LangWidget::validate($langModel,$post)) {
                try {
                    $this->tag_service->edit($id, $form);
                    $resultat = $langModel->updateLang($post['TagLang'],$id);

                    Yii::$app->session->setFlash('success', 'Тег обновлен');
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
            $this->tag_service->changeStatus($post['id'],$post['checked']);
            Yii::$app->session->setFlash('success',StatusHelper::infoFlash($post['checked'],'Тег'));

            return $this->redirect(Url::toRoute('/blog/tag/index'));
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }

    public function actionDelete($id)
    {
        try {
            $this->tag_service->remove($id);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }

    protected function findModel($id): Tag
    {
        if (($model = Tag::find()->where(['id' => $id])->with(['manyLang','aliasLang'])->one()) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
