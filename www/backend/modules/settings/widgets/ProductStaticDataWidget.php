<?php

namespace backend\modules\settings\widgets;
use backend\modules\settings\models\ProductStaticData;
use Yii;
use yii\base\Widget;


class ProductStaticDataWidget extends Widget
{
    public $template;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $view = Yii::getAlias('@backend').'/modules/settings/views/product-static-data/' . $this->template . '.php';

        if (!file_exists($view)) {
            return 'Неверно задан параметр $template';
        }

        $data = ProductStaticData::find()->where(['status' => 1])->all();

        return $this->renderFile($view,[
            'data' => $data
        ]);
    }
}