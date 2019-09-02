<?php

namespace backend\modules\content\controllers;

use backend\modules\content\models\SeoData;
use backend\modules\content\models\SlugManager;
//use backend\modules\user\useCase\Access;
use Yii;
use backend\modules\content\models\Channel;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ChannelController implements the CRUD actions for Channel model.
 */
class ChannelController extends Controller
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
                        'actions' => ['index','create','update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Channel models.
     *
     * @perm('Список всех каналов (контент)')
     *
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionIndex()
    {
//        $this->access->accessAction();

        $query = Channel::find();
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
     * Creates a new Channel model.
     *
     * @perm('Создание нового канала (контент)')
     *
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionCreate()
    {
//        $this->access->accessAction();

        $cache = YII::$app->cache;
        $cache->delete('block-id');

        $model = new Channel();
        $seo = new SeoData();
        $route = new SlugManager();

        if ($model->load(Yii::$app->request->post())) {
            $model->created_at = $model->updated_at = date('Y-m-d');
            if($this->saveChannel($model,$seo, $route)) {
                if(Yii::$app->request->post('redirect') == 1) {
                    return $this->redirect(['index']);
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
        ]);
    }

    /**
     * Updates an existing Channel model.
     *
     * @perm('Обновление выбранного канала (контент)')
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
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->updated_at = date('Y-m-d');
            $seo = $model->seoData;
            $route = $model->slugManager;
            if($this->saveChannel($model,$seo, $route)) {
                if(Yii::$app->request->post('redirect') == 1) {
                    return $this->redirect(['index', 'id' => $model->id]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'seo' => $model->seoData,
            'route' => $model->slugManager,
            'contentBlocks' => $model->channelContent,
            'commonFields' => $model->channelRecordsCommonField,
        ]);
    }

    /**
     * Deletes an existing Channel model.
     *
     * @perm('Удаление выбранного канала (контент)')
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

    protected function saveChannel(Channel $channel, $seo, $route)
    {
        $seo->attributes = Yii::$app->request->post('SeoData');
        $channel->seoData = $seo;

        $route->attributes = Yii::$app->request->post('slug');
        $channel->slugManager = $route;

        $blocks = Yii::$app->request->post('block');
        if($blocks) {
            $channel->channelContent = array_map(function($row) {
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

        $channel->record_structure = serialize($channel->record_structure);

        $recordsCommonFields = Yii::$app->request->post('commonFields');
        if($recordsCommonFields) {
            $channel->channelRecordsCommonField = array_map(function($row) {
                $row['content'] = $row['text'];
                unset($row['text']);
                return $row;
            }, $recordsCommonFields);
        }

        if($channel->save()) {
            Yii::$app->session->setFlash('success', "<p>Данные сохранены</p>");
            return true;
        }
        else {
            $errors = '';
            foreach ($channel->getErrors() as $fieldWithErrors) {
                foreach ($fieldWithErrors as $error) {
                    $errors .= '<p>' . $error . '</p>';
                }
            }
            Yii::$app->session->setFlash('error', $errors);
        }
        return false;
    }

    /**
     * Finds the Channel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Channel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Channel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}