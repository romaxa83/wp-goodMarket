<?php

namespace backend\modules\seo\controllers;

use Yii;
use yii\web\Controller;
use backend\modules\seo\models\SeoMeta;
use backend\controllers\BaseController;
use common\controllers\AccessController;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

/**
 * Default controller for the `seo` module
 */
class DefaultController extends BaseController
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
    
    /**
     * Достает запись с сео данными 
     * @return object 
     */
    public function actionIndex($id = null)
    {
        if($id != null){
            $model = $this->language($groups = SeoMeta::find()->where(['id' => $id])->orWhere(['parent_id' => $id])->all(), ['h1', 'title','keywords','description','seo_text']);
            if($model == null)$model = new SeoMeta();
        }else {
            $model = new SeoMeta();
        }
        return $model;

    }
}
