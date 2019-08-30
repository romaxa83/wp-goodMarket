<?php

namespace backend\modules\banners\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use backend\controllers\BaseController;
use backend\modules\banners\models\Banner;
use backend\widgets\langwidget\LangWidget;
use common\controllers\AccessController;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

class BannersController extends BaseController {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => AccessController::getAccessRules(Yii::$app->controller),
                'denyCallback' => function($rule, $action) {
                    throw new ForbiddenHttpException('Вам не разрешено производить данное действие.');
                }
            ],
        ];
    }

    public function beforeAction($action) {
        if (parent::beforeAction($action)) {
            if (!AccessController::checkPermission($action->controller->route)) {
                throw new ForbiddenHttpException('Вам не разрешено производить данное действие.');
            }
            return parent::beforeAction($action);
        } else {
            return false;
        }
    }

    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => Banner::find()->orderBy('position'),
            'sort' => false,
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);
        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'user_settings' => Yii::$app->user->identity->getSettings('banner')
        ]);
    }

    private function updateStatus($id, $status) {
        $active_banner = Banners::findOne($id);
        $active_banner->publication = $status;
        $active_banner->save();
    }

    private function createDefaultBanner() {
        $banner = new Banners();
        $banner->parent = 0;
        $banner->language = 'ru';
        $banner->title = 'default title';
        $banner->text = 'default text';
        $banner->alias = 'link';
        $banner->position = NULL;
        $banner->publication = 0;
        $banner->media_id = 0;
        $banner->save();
        return $banner;
    }

    public function actionUpdateStatus() {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->post()) {
                $data = Yii::$app->request->post();
                $record_id = $data['id'];
                $status = $data['checked'];
                $this->updateStatus($record_id, $status);
            }
        }
    }

    public function actionUpdatePositions() {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->post()) {
                $data = Yii::$app->request->post('banner-row');
                foreach ($data as $key => $value) {
                    Banners::updateAll(['position' => $key], ['=', 'id', $value]);
                }
            }
        }
    }

    public function actionCreate($id = 0) {
        if ($id != 0) {
            $model = Banner::findOne($id);
            $model->languageData = Banners::find()->select(['title', 'text', 'language'])->asArray()->where(['id' => $id])->orWhere(['parent' => $id])->all();
            $title = 'Редактировать';
        } else {
            $model = new Banner();
            $title = 'Добавить баннер';
        }
        return $this->render('form', [
                    'title' => $title,
                    'model' => $model
        ]);
    }

    public function actionEditBanner($id) {
        $model = Banners::find($id)->one();
        if (($model->load(Yii::$app->request->post())) && (LangWidget::validate($model))) {
            $data = Yii::$app->request->post();
            foreach (LangWidget::getActiveLanguageData(['alias']) as $v) {
                $alias = $v['alias'];
                $data_lang = $data['Banners']['Language'][$alias];
                $banner = Banners::find()->where(['language' => $alias])->andWhere(['id' => $id])->orWhere(['parent' => $id])->one();
                $banner->title = $data_lang['title'];
                $banner->text = $data_lang['text'];
                $banner->alias = $data['Banners']['alias'];
                $banner->publication = $data['Banners']['publication'];
                $banner->media_id = $data['Banners']['media_id'];
                $banner->save();
            }
            Yii::$app->session->setFlash('success', 'Пункт успешно отредактирован');
            return $this->redirect(['/banners/banners']);
        }
    }

    public function actionAddBanner() {
        $model = new Banners();
        if (($model->load(Yii::$app->request->post())) && (LangWidget::validate($model))) {
            $last_id = 0;
            $data = Yii::$app->request->post();
            foreach (LangWidget::getActiveLanguageData(['alias']) as $v) {
                $banner = new Banners();
                $alias = $v['alias'];
                $data_lang = $data['Banners']['Language'][$alias];
                $banner->parent = $last_id;
                $banner->language = $alias;
                $banner->title = $data_lang['title'];
                $banner->text = $data_lang['text'];
                $banner->alias = $data['Banners']['alias'];
                $banner->position = NULL;
                $banner->publication = $data['Banners']['publication'];
                $banner->media_id = $data['Banners']['media_id'];
                $banner->save();
                $last_id = ($last_id === NULL) ? $banner->id : $last_id;
            }
            Yii::$app->session->setFlash('success', 'Пункт успешно добавлен');
            return $this->redirect(['/banners/banners']);
        }
    }

    public function actionDeleteBanner() {
        $id = Yii::$app->request->get('id');
        $model = Banners::findOne($id);
        Banners::deleteAll('id = :id OR parent = :id', ['id' => $id]);
        Yii::$app->session->setFlash('success', 'Пункт успешно удален');
        $this->redirect(['/banners/banners']);
    }

}
