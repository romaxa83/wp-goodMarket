<?php

namespace backend\controllers;

use yii\web\Controller;
use Yii;
use yii\web\ForbiddenHttpException;

class BaseController extends Controller {

    public function init() {
        parent::init();
        if(Yii::$app->user->isGuest){
            $path = Yii::$app->request->pathinfo;
            if(!Yii::$app->request->isAjax && $path !== 'site/login'){
                return $this->redirect('site/login');
            }
        }
    }
    /**
     * Свой деббагер
     * @param $item
     */
    public function d($item) {
        echo "<pre>";
        print_r($item);
        echo "</pre>";
        die;
    }
    
    
}
