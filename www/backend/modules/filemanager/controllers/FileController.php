<?php

namespace backend\modules\filemanager\controllers;

use backend\modules\filemanager\models\MediafileSearch;
use backend\modules\filemanager\models\Tag;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use backend\modules\filemanager\FileManager;
use backend\modules\filemanager\models\Mediafile;
use backend\modules\filemanager\assets\FilemanagerAsset;
use yii\helpers\Url;
use common\controllers\AccessController;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

class FileController extends Controller
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'filemanager', 'uploadmanager', 'upload', 'update', 'delete','resize','info'],
                'rules' => AccessController::getAccessRules(Yii::$app->controller),
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'update' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (!AccessController::checkPermission($action->controller->route)) {
                throw new ForbiddenHttpException('Вам не разрешено производить данное действие.');
            }
            if (defined('YII_DEBUG') && YII_DEBUG) {
                Yii::$app->assetManager->forceCopy = true;
            }
            return parent::beforeAction($action);
        } else {
            return false;
        }
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionFilemanager()
    {
        $this->layout = '@filemanager/views/layouts/main';
        $model = new MediafileSearch();
        $tagTable = new Tag();
        $tagList = $tagTable::find()->asArray()->all();
        $dataProvider = $model->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->defaultPageSize = 15;

        return $this->render('filemanager', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'tagList' => $tagList,
        ]);
    }

    public function actionUploadmanager()
    {
        $this->layout = '@filemanager/views/layouts/main';
        return $this->render('uploadmanager', ['model' => new Mediafile()]);
    }

    /**
     * Provides upload file
     * @return mixed
     */
    public function actionUpload()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new Mediafile();
        $routes = $this->module->routes;
        $rename = $this->module->rename;
	    $tagIds = Yii::$app->request->post('tagIds');

	    if ($tagIds !== 'undefined') {
		    $model->setTagIds(explode(',', $tagIds));
	    }

        $model->saveUploadedFile($routes, $rename);
        $bundle = FilemanagerAsset::register($this->view);

        if ($model->isImage()) {
            $model->createThumbs($routes, $this->module->thumbs);
        }

        $response['files'][] = [
            'url'           => $model->url,
            'thumbnailUrl'  => '/admin'. $model->getDefaultThumbUrl($bundle->baseUrl),
            'name'          => $model->filename,
            'type'          => $model->type,
            'size'          => $model->file->size,
            'deleteUrl'     => Url::to(['file/delete', 'id' => $model->id]),
            'deleteType'    => 'POST',
        ];

        return $response;
    }

    /**
     * Updated mediafile by id
     * @param $id
     * @return array
     */
    public function actionUpdate($id)
    {
        $model = Mediafile::findOne($id);
        $message = FileManager::t('main', 'Changes not saved.');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $message = FileManager::t('main', 'Changes saved!');
        }

        Yii::$app->session->setFlash('mediafileUpdateResult', $message);

        Yii::$app->assetManager->bundles = false;
        return $this->renderAjax('info', [
            'model' => $model,
            'strictThumb' => null,
        ]);
    }

    /**
     * Delete model with files
     * @param $id
     * @return array
     */
    public function actionDelete($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $routes = $this->module->routes;

        $model = Mediafile::findOne($id);

        if ($model->isImage()) {
            $model->deleteThumbs($routes);
        }

        $model->deleteFile($routes);

        if(!$model->deleteEssenceImg($id, Yii::$app->params['media']['default_img'])){
            return ['success' => 'false'];
        }
        $model->delete();
        return ['success' => 'true'];
    }

    /**
     * Resize all thumbnails
     */
    public function actionResize()
    {
        $models = Mediafile::findByTypes(Mediafile::$imageFileTypes);
        $routes = $this->module->routes;

        foreach ($models as $model) {
            if ($model->isImage()) {
                $model->deleteThumbs($routes);
                $model->createThumbs($routes, $this->module->thumbs);
            }
        }

        Yii::$app->session->setFlash('successResize');
        $this->redirect(Url::to(['default/settings']));
    }

    /** Render model info
     * @param int $id
     * @param string $strictThumb only this thumb will be selected
     * @return string
     */
    public function actionInfo($id, $strictThumb = null)
    {
        $model = Mediafile::findOne($id);
        Yii::$app->assetManager->bundles = false;
        return $this->renderAjax('info', [
            'model' => $model,
            'strictThumb' => $strictThumb,
        ]);
    }
}
