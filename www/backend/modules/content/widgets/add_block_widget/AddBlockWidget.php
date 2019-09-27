<?php

namespace backend\modules\content\widgets\add_block_widget;

use Yii;
use yii\base\Widget;

class AddBlockWidget extends Widget {
    public $parentContainerId;
    public $group = 'block';

    public function init() {
        parent::init();
        Yii::setAlias('@add-block-assets', __DIR__ . '/assets');
        AddBlockWidgetAssets::register(Yii::$app->view);
    }

    public function run() {
        return $this->render('form', [
            'parentContainerId' => $this->parentContainerId,
            'group' => $this->group,
        ]);
    }

}
