<?php

namespace backend\modules\content\controllers;

use Yii;

use Ausi\SlugGenerator\SlugGenerator;
use backend\modules\content\models\ContentOptions;
use backend\modules\content\models\SlugManager;

//use backend\modules\user\useCase\Access;

use backend\modules\content\models\Page;
use backend\modules\content\models\PageLang;
use backend\modules\content\models\PageMeta;
use backend\modules\content\models\PageMetaLang;
use backend\modules\content\models\PageText;
use backend\widgets\langwidget\LangWidget;

use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;

use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends Controller
{
    protected $access;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);

//        $this->access = new Access();
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors() 
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','create','update', 'delete', 'ajax-generate-alias', 'get-route-for-template'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Page models.
     *
     * @perm('Список страниц (контент)')
     *
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionIndex() 
    {
//        $this->access->accessAction();

        $query = Page::find()->with(['oneLang']);

        $search = Yii::$app->request->get('search');
        if ($search) {
            $query->andFilterWhere([
                'like',
                'title',
                $search
            ]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Page model.
     *
     * @perm('Создание новой страницы (контент)')
     *
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionCreate()
    {
//        $this->access->accessAction();
        $cache = YII::$app->cache;
        $cache->delete('block-id');

        $model = new Page();
        $langModel = new PageLang();

        $seo = new PageMeta();
        $langSeo = new PageMetaLang();
        
        $slug = new SlugManager();

        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            if($model->load($post) && LangWidget::validate($langModel,$post) && LangWidget::validate($langSeo,$post)){
                if($this->savePage($model, $seo, $slug)) {
                    $langModel->saveLang($post['PageLang'],$model->id);
                    $langSeo->saveLang($post['PageMetaLang'],$model->id);

                    if(Yii::$app->request->post('redirect') == 1) {
                        return $this->redirect(['index', 'id' => $model->id]);
                    }else{
                        return $this->redirect(['update?id=' . $model->id]);
                    }
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'langModel' => $langModel,
            'seo' => $seo,
            'langSeo' => $langSeo,
            'slug' => $slug,
        ]);
    }

    /**
     * Updates an existing Page model.
     *
     * @perm('Обновление выбранной страницы (контент)')
     *
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) 
    {
//        $this->access->accessAction();

        $cache = YII::$app->cache;
        $cache->delete('block-id');

        $model = Page::find()->where(['id' => $id])->with(['manyLang','aliasLang'])->one();
        $langModel = new PageLang();

        foreach ($model['manyLang'] as $indexRow => $oneLang) {
            $langAlias = $model['aliasLang'][$indexRow]->alias;
            $langModel->languageData[$langAlias]['title'] = $oneLang->title;
        }

        $seo = PageMeta::find()->where(['id' => $id])->with(['manyLang','aliasLang'])->one();
        $langSeo = new PageMetaLang();

        foreach ($seo['manyLang'] as $indexRow => $oneLang) {
            $langAlias = $seo['aliasLang'][$indexRow]->alias;
            $langSeo->languageData[$langAlias]['title'] = $oneLang->title;
            $langSeo->languageData[$langAlias]['description'] = $oneLang->description;
            $langSeo->languageData[$langAlias]['keywords'] = $oneLang->keywords;
        }

        $slug = $model->slugManager;

        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            if ($model->load($post) && LangWidget::validate($langModel,$post)) {
                if($this->savePage($model, $seo, $slug)) {
                    $langModel->updateLang($post['PageLang'],$id);
                    $langSeo->updateLang($post['PageMetaLang'],$id);

                    if(Yii::$app->request->post('redirect') == 1) {
                        return $this->redirect(['index', 'id' => $model->id]);
                    }else{
                        return $this->redirect(['update?id=' . $id]);
                    }
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'langModel' => $langModel,
            'seo' => $seo,
            'langSeo' => $langSeo,
            'textBlocks' => $model->pageText,
            'slug' => $model->slugManager,
        ]);
    }

    /**
     * Deletes an existing Page model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @perm('Удаленние выбранной страницы (контент)')
     *
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id) 
    {
//        $this->access->accessAction();

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @perm('Генерация слага (контент)')
     *
     * @return bool|string
     * @throws ForbiddenHttpException
     */
    public function actionAjaxGenerateAlias()
    {
//        $this->access->accessAction();
        if(Yii::$app->request->isAjax){
            if($title = Yii::$app->request->post('title')){
                $generator = new SlugGenerator;
                $alias = $generator->generate($title);
                return $alias;
            }
        }

        return false;
    }

    /**
     * @perm('Получить роут для шаблона (контент)')
     *
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionGetRouteForTemplate() 
    {
//        $this->access->accessAction();

        $slug = Yii::$app->request->post('template');
        $template = Yii::$app->getModule('content')->params['templates'][$slug];
        $route = $template['route'];
        return $route;
    }

    private function savePage(Page $model, $seo, $slug)
    {
        $seo->attributes = Yii::$app->request->post('PageMeta');
        $model->pageMetas = $seo;
        $slug->attributes = Yii::$app->request->post('slug');
        $model->slugManager = $slug;

        $texts = Yii::$app->request->post('block');
        if($texts) {
            $model->pageText = PageText::preparePostData($texts);
        }
        if($model->save()) {
            Yii::$app->session->setFlash('success', "<p>Данные сохранены</p>");
            return true;
        }
        else {
            $errors = '';
            foreach ($model->getErrors() as $fieldWithErrors) {
                foreach ($fieldWithErrors as $error) {
                    $errors .= '<p>' . $error . '</p>';
                }
            }
            Yii::$app->session->setFlash('error', $errors);
        }
        return false;
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) 
    {
        if (($model = Page::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
