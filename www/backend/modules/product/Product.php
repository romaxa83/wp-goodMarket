<?php

namespace backend\modules\product;

use Yii;
use yii\base\Module;

class Product extends Module {

    public $controllerNamespace = 'backend\modules\product\controllers';

    public function init() {
        parent::init();

        $this->setAliases([
            '@product-assets' => __DIR__ . '/assets'
        ]);

        Yii::configure($this, require __DIR__ . '/config/config.php');
    }

}
