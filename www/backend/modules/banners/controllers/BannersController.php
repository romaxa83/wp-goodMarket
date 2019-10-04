<?php

namespace backend\modules\banners\controllers;

use backend\modules\filemanager\models\Mediafile;
use Yii;
use yii\data\ActiveDataProvider;
use backend\controllers\BaseController;
use backend\modules\banners\models\Banner;
use backend\widgets\langwidget\LangWidget;
use common\controllers\AccessController;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use backend\modules\banners\models\BannerLang;

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
            'query' => Banner::find()->with('bannerLang')->with('bannerLang.media')->orderBy('position'),
            'sort' => FALSE,
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);
        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'user_settings' => Yii::$app->user->identity->getSettings('banner')
        ]);
    }

    public function actionCreate() {
        $this->view->title = 'Добавить баннер';
        $this->view->params['breadcrumbs'][] = $this->view->title;
        $model = new Banner();
        $modelLang = new BannerLang();
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if (BannerLang::saveAll($model, $modelLang, $post)) {
                BannerLang::cropBanner($model->id);
                Yii::$app->session->setFlash('success', 'Пункт успешно добавлен');
                return $this->redirect(['/banners/banners']);
            }
        }
        return $this->render('form', [
                    'model' => $model,
                    'modelLang' => $modelLang
        ]);
    }

    public function actionUpdate() {
        $this->view->title = 'Редактировать баннер';
        $this->view->params['breadcrumbs'][] = $this->view->title;
        $id = Yii::$app->request->get('id');
        $model = Banner::find()->where(['id' => $id])->with('bannerLang')->one();
        $modelLang = BannerLang::find()->where(['banner_id' => $id])->one();
        $data = [];
        foreach ($model->bannerLang as $v) {
            foreach ($v as $k1 => $v1) {
                $data[$v->lang->alias][$k1] = $v1;
            }
        }
        $modelLang->languageData = $data;
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if (BannerLang::saveAll($model, $modelLang, $post)) {
                BannerLang::cropBanner($id);
                Yii::$app->session->setFlash('success', 'Пункт успешно отредактирован');
                return $this->redirect(['/banners/banners']);
            }
        }
        return $this->render('form', [
                    'model' => $model,
                    'modelLang' => $modelLang
        ]);
    }

    public function actionDelete() {
        $id = Yii::$app->request->get('id');
        foreach (BannerLang::find()->where(['banner_id' => $id])->all() as $b) {
            $b->delete();
        }
        Banner::find()->where(['id' => $id])->one()->delete();
        Yii::$app->session->setFlash('success', 'Пункт успешно удален');
        $this->redirect(['/banners/banners']);
    }

    public function actionUpdateStatus() {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->post()) {
                $data = Yii::$app->request->post();
                $banner = Banner::findOne($data['id']);
                $banner->status = $data['checked'];
                $banner->save();
            }
        }
    }

    public function actionUpdatePosition() {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->post()) {
                $data = Yii::$app->request->post('banner-row');
                foreach ($data as $key => $value) {
                    Banner::updateAll(['position' => $key], ['=', 'id', $value]);
                }
            }
        }
    }

}
