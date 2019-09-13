<?php

namespace backend\modules\import;

use Yii;
use yii\base\Module;

class Import extends Module {

    public $controllerNamespace = 'backend\modules\import\controllers';

    public function init() {
        parent::init();

        $this->setAliases([
            '@import-assets' => __DIR__ . '/assets'
        ]);

        ImportAssets::register(Yii::$app->view);

        Yii::configure($this, require __DIR__ . '/config/config.php');
    }

}
