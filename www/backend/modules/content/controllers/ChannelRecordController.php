<?php

namespace backend\modules\content\controllers;

use backend\modules\content\models\Channel;
use backend\modules\content\models\ChannelCategory;
use backend\modules\content\models\ChannelCategoryRecord;
use backend\modules\content\models\ChannelRecordContent;
use backend\modules\content\models\SeoData;
use backend\modules\content\models\SlugManager;
//use backend\modules\user\useCase\Access;
use Yii;
use backend\modules\content\models\ChannelRecord;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
/**
 * ChannelRecordController implements the CRUD actions for ChannelRecord model.
 */
class ChannelRecordController extends Controller
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
    public function behaviors() {
        return [
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ChannelRecord models.
     *
     * @perm('Список всех записей выбранного канала (контент)')
     *
     * @param integer $channel_id
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionIndex($channel_id)
    {
//        $this->access->accessAction();

        $query = ChannelRecord::find()->andWhere(['channel_id' => $channel_id]);

        $search = Yii::$app->request->get('search-r');
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

        $channel = Channel::findOne(['id' => $channel_id]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'channel' => $channel,
        ]);
    }

    /**
     * Creates a new ChannelRecord model.
     *
     * @perm('Создание новой записи для выбранного канала (контент)')
     *
     * @param integer $channel_id
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionCreate($channel_id)
    {
//        $this->access->accessAction();

        $cache = YII::$app->cache;
        $cache->delete('block-id');

        $model = new ChannelRecord();
        $seo = new SeoData();
        $route = new SlugManager();

        $channel = Channel::findOne(['id' => $channel_id]);
        $categories = ChannelCategory::find()
            ->where([
                'and',
                ['channel_id' => $channel_id],
                ['status' => 1]
            ])
            ->all();

        $structure = $channel->record_structure;

        $model->channel_id = $channel_id;

        if ($model->load(Yii::$app->request->post())) {
            $model->created_at = $model->updated_at = date('Y-m-d');
            $blocks = Yii::$app->request->post('block');
            if($this->saveChannelRecord($model,$seo, $route, $blocks)) {
                if(Yii::$app->request->post('redirect') == 1) {
                    return $this->redirect(['index', 'channel_id' => $model->channel_id]);
                }
                else {
                    return $this->redirect(['update', 'id' => $model->id]);
                }
            }
            $blocks = $model->channelRecordContent;
            $structure = serialize($blocks);
        }

        return $this->render('create', [
            'model' => $model,
            'seo' => $seo,
            'route' => $route,
            'structure' => $structure,
            'categories' => $categories,
        ]);
    }

    /**
     * Updates an existing ChannelRecord model.
     *
     *  @perm('Обновление выбранной записи (контент)')
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

        $categories = ChannelCategory::find()
            ->where([
                'and',
                ['channel_id' => $model->channel_id],
                ['status' => 1]
            ])
            ->all();

        $blocks = $model->channelRecordContent;
        //$structure = $model->channel->record_structure;
        unset($blocks['content']);

        if ($model->load(Yii::$app->request->post())) {
            $model->updated_at = date('Y-m-d');
            $seo = $model->seoData;
            $route = $model->slugManager;
            $blocks = Yii::$app->request->post('block');
            if($this->saveChannelRecord($model,$seo, $route, $blocks)) {
                if(Yii::$app->request->post('redirect') == 1) {
                    return $this->redirect(['index', 'channel_id' => $model->channel_id]);
                }
            }
            $blocks = $model->channelRecordContent;
        }
        $blocks = serialize($blocks);

        return $this->render('update', [
            'model' => $model,
            'seo' => $model->seoData,
            'route' => $model->slugManager,
            'structure' => $blocks,
            'categories' => $categories,
        ]);
    }

    /**
     * Deletes an existing ChannelRecord model.
     *
     * @perm('Удаление выбранной записи (контент)')
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

        $model = $this->findModel($id);
        $channel_id = $model->channel->id;

        $model->delete();



        return $this->redirect(['index', 'channel_id' => $channel_id]);
    }

    protected function saveChannelRecord(ChannelRecord $channelRecord, SeoData $seo, SlugManager $route, $blocks)
    {
        $seo->attributes = Yii::$app->request->post('SeoData');
        $channelRecord->seoData = $seo;

        $route->attributes = Yii::$app->request->post('slug');
        $route->post_id = $channelRecord->id;
        $route->parent_id = $channelRecord->channel->slugManager->id;
        $channelRecord->slugManager = $route;

        if($blocks) {
            $channelRecord->channelRecordContent = array_map(function($row) {
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


        if($channelRecord->save()) {
            $channelRecord->slugManager->post_id = $channelRecord->id;
            $channelRecord->slugManager->save();

            $this->attachCategories($channelRecord);

            Yii::$app->session->setFlash('success', "<p>Данные сохранены</p>");
            return true;
        }
        else {
            $errors = '';
            foreach ($channelRecord->getErrors() as $fieldWithErrors) {
                foreach ($fieldWithErrors as $error) {
                    $errors .= '<p>' . $error . '</p>';
                }
            }
            Yii::$app->session->setFlash('error', $errors);
        }
        return false;
    }

    /**
     * Finds the ChannelRecord model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ChannelRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = ChannelRecord::find()->where(['id' => $id])->with('channelRecordContent')->one();
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param ChannelRecord $channelRecord
     * @throws \yii\db\Exception
     */
    protected function attachCategories(ChannelRecord $channelRecord)
    {
        $categories = Yii::$app->request->post('categories');
        $activeCategories = ChannelCategoryRecord::getRecordActiveCategoriesId($channelRecord->id);
        if ($categories) {
            $categoriesToInsert = array();
            foreach ($categories as $key => $category) {
                if (!in_array($key, $activeCategories)) {
                    $categoriesToInsert[] = array(
                        'category_id' => $key,
                        'record_id' => $channelRecord->id,
                    );
                }
            }

            $categoriesToDelete = array();
            foreach ($activeCategories as $categoryId) {
                if (!in_array($categoryId, $categories)) {
                    $categoriesToDelete[] = $categoryId;
                }
            }

            if (!empty($categoriesToInsert)) {
                $columns = array('category_id', 'record_id');
                Yii::$app->db->createCommand()->batchInsert('channel_category_record', $columns, $categoriesToInsert)->execute();
            }

            if (!empty($categoriesToDelete)) {
                Yii::$app->db->createCommand()
                    ->delete('channel_category_record', [
                        'category_id' => $categoriesToDelete,
                        'record_id' => $channelRecord->id
                    ])
                    ->execute();
            }
        } else {
            if ($activeCategories) {
                Yii::$app->db->createCommand()
                    ->delete('channel_category_record', [
                        'category_id' => $activeCategories,
                        'record_id' => $channelRecord->id
                    ])
                    ->execute();
            }
        }
    }
}