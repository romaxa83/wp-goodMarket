<?php

namespace frontend\widgets\langWidget;

use Yii;
use yii\base\Widget;
use frontend\widgets\langWidget\LangWidgetAssets;

class LangWidget extends Widget 
{
    public $mobile = false;

    public function init() 
    {
        parent::init();
        Yii::setAlias('@langwidget-assets', __DIR__ . '/assets');
        LangWidgetAssets::register(Yii::$app->view);
    }

    public function run() 
    {
        if($this->mobile){
            return $this->render('switch-lang-mob');
        }else{
            return $this->render('switch-lang');
        }
    }

}
