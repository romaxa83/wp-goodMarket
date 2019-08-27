<?php

namespace backend\modules\settings\controllers;

use Yii;
use backend\controllers\BaseController;
use backend\modules\settings\helpers\StaticDataHelper;
use backend\modules\settings\models\ProductStaticData;
use yii\helpers\Html;
use yii\helpers\Url;
use common\controllers\AccessController;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

class ProductStaticDataController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => AccessController::getAccessRules(Yii::$app->controller),
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (!AccessController::checkPermission($action->controller->route)) {
                throw new ForbiddenHttpException('Вам не разрешено производить данное действие.');
            }
            return parent::beforeAction($action);
        } else {
            return false;
        }
    }

    public function actionIndex()
    {
        $data = ProductStaticData::find()->all();

        return $this->render('index',[
            'data' => $data
        ]);
    }

    public function actionUpdateStatus()
    {
        if(\Yii::$app->request->isAjax){
            $post = \Yii::$app->request->post();

            $model = ProductStaticData::find()->where(['alias' => $post['id']])->one();
            $model->status = $post['checked'];
            $model->update();

            $text = $post['checked'] == 0 ? 'отключен' : 'включен' ;
            \Yii::$app->session->setFlash(
                'success',
                'Блок ' . $text
            );

            return $this->redirect(Url::toRoute(['/settings/product-static-data/index']));
        }
    }

    public function actionEdit($id)
    {
        $model = ProductStaticData::find()->where(['id' => $id])->one();

        if($model->load(\Yii::$app->request->post())){

            $post = \Yii::$app->request->post();

            $model->title = Html::encode($post['ProductStaticData']['title']);
            $model->description = Html::encode(substr(strip_tags(str_replace('</li>','|',$post['ProductStaticData']['description'])),0,-1));

            $model->update();

            return $this->redirect(Url::toRoute(['/settings/product-static-data/index']));
        }
        return $this->render('edit',[
            'model' => $model
        ]);
    }

}