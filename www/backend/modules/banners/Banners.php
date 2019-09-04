<?php

namespace backend\modules\banners;

use yii\base\Module;

/**
 * Класс определения модуля 'banners'
 */
class Banners extends Module {

    /**
     * В свойстве храниться пространство имен модуля
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-module#$controllerNamespace-detail
     * @var string
     */
    public $controllerNamespace = 'backend\modules\banners\controllers';

    /**
     * Инициализация модуля
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-module#init()-detail
     */
    public function init() {
        parent::init();
        $this->setAliases([
            '@banners-assets' => __DIR__ . '/assets'
        ]);
        $this->setAliases([
            '@banners-img' => __DIR__ . '/assets/img'
        ]);
    }

}
