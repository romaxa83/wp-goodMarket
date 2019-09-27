<?php

namespace backend\modules\content\widgets\select_template_widget;


use Yii;
use yii\base\Widget;

class SelectTemplateWidget extends Widget
{
    public $slugId;
    public $slugRoute;
    public $routeToAction;
    public $value;
    public $templates;

    public function init()
    {
        parent::init();
        Yii::setAlias('@select-template-assets', __DIR__ . '/assets');
        SelectTemplateWidgetAsset::register(Yii::$app->view);
    }

    public function run()
    {
        return $this->render('form', [
            'slugId' => $this->slugId,
            'slugRoute' => $this->slugRoute,
            'routeToAction' => $this->routeToAction,
            'value' => $this->value,
            'templates' => $this->templates,
        ]);
    }
}