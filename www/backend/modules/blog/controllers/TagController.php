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
        $this->tag_lang = new TagLang();
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
        $post = Yii::$app->request->post();

        if ($form->load($post) && $form->validate()) {
            try {
                $form->title = $post['TagForm']['Language']['ru'];
                $tag = $this->tag_service->create($form);
                $resultat = $this->tag_lang->saveLang($post['TagForm']['Language'],$tag->id);

                return $this->redirect(['update?id='.$tag->id]);
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
        $tag = $this->findModel($id);
        //get by related model with lang
        $relatedRecord = $tag->getRelatedRecords();
        $form = new TagForm($tag);
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

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $form->title = $post['TagForm']['Language']['ru'];
                $this->tag_service->edit($tag->id, $form);
                $resultat = $this->tag_lang->updateLang($post['TagForm']['Language'],$tag->id);

                return $this->redirect(['update?id='.$tag->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', [
            'model' => $form,
            'tag' => $tag,
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
        if (($model = Tag::find()->where(['id' => $id])->with('title')->one()) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
