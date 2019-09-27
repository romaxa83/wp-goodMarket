<?php

namespace backend\modules\content\controllers;

use backend\modules\content\models\Channel;
use backend\modules\content\models\ChannelCategoryContent;
use backend\modules\content\models\SeoData;
use backend\modules\content\models\SlugManager;
//use backend\modules\user\useCase\Access;
use Yii;
use backend\modules\content\models\ChannelCategory;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ChannelCategoryController implements the CRUD actions for ChannelCategory model.
 */
class ChannelCategoryController extends Controller
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
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all ChannelCategory models.
     *
     * @perm('Список всех категорий выбранного канала (контент)')
     *
     * @param $channel_id
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionIndex($channel_id)
    {
//        $this->access->accessAction();

        $dataProvider = new ActiveDataProvider([
            'query' => ChannelCategory::find()->andWhere(['channel_id' => $channel_id]),
        ]);

        $channel = Channel::findOne(['id' => $channel_id]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'channel' => $channel,
        ]);
    }

    /**
     * Creates a new ChannelCategory page.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @perm('Создание новой категории выбранного канала (контент)')
     *
     * @param $channel_id
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionCreate($channel_id)
    {
//        $this->access->accessAction();

        $cache = YII::$app->cache;
        $cache->delete('block-id');

        $model = new ChannelCategory();
        $model->channel_id = $channel_id;

        $seo = new SeoData();
        $route = new SlugManager();
        //$content = new ChannelCategoryContent();
        $channel = Channel::findOne(['id' => $channel_id]);

        if ($model->load(Yii::$app->request->post())) {
            $model->created_at = date('Y-m-d');
            if($this->saveChannelCategory($model,$seo, $route)) {
                if(Yii::$app->request->post('redirect') == 1) {
                    return $this->redirect(['index', 'channel_id' => $model->channel_id]);
                }
                else {
                    return $this->redirect(['update', 'id' => $model->id]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'seo' => $seo,
            'route' => $route,
            'channel' => $channel,
            'content' => array()
        ]);
    }

    /**
     * Updates an existing ChannelCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
//        $this->access->accessAction();

        $cache = YII::$app->cache;
        $cache->delete('block-id');
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $seo = $model->seoData;
            $route = $model->slugManager;
            if($this->saveChannelCategory($model,$seo, $route)) {
                if(Yii::$app->request->post('redirect') == 1) {
                    return $this->redirect(['index', 'channel_id' => $model->channel_id]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'seo' => $model->seoData,
            'route' => $model->slugManager,
            'content' => $model->channelCategoryContent
        ]);
    }

    /**
     * Deletes an existing ChannelCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the ChannelCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ChannelCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ChannelCategory::find()->where(['id' => $id])->with('channel')->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function saveChannelCategory(ChannelCategory $category, $seo, $route)
    {
        $blocks = Yii::$app->request->post('block');
        if($blocks) {
            $category->channelCategoryContent = array_map(function($row) {
                if(!is_array($row['text'])) {
                    $row['content'] = $row['text'];
                    unset($row['text']);
                    return $row;
                }
                $row['content'] = serialize($row['text']);
                unset($row['text']);
                return $row;
            }, $blocks);
        }

        if($category->save()) {
            Yii::$app->session->setFlash('success', "<p>Данные сохранены</p>");
            return true;
        }
        else {
            $errors = '';
            foreach ($category->getErrors() as $fieldWithErrors) {
                foreach ($fieldWithErrors as $error) {
                    $errors .= '<p>' . $error . '</p>';
                }
            }
            Yii::$app->session->setFlash('error', $errors);
        }

        return false;
    }
}