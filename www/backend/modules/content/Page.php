<?php

namespace backend\modules\content;

use Yii;
use yii\base\Module;

class Page extends Module {

    public $controllerNamespace = 'backend\modules\content\controllers';

    public function init() {
        parent::init();

        Yii::configure($this, require __DIR__ . '/config/config.php');
    }

}
