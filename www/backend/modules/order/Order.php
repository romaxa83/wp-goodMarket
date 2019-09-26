<?php

namespace backend\modules\order;

use backend\controllers\BaseController;

/**
 * Класс определения модуля 'blog'
 */
class Order extends \yii\base\Module
{
    /**
     * В свойстве храниться пространство имен модуля
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-module#$controllerNamespace-detail
     * @var string
     */
    public $controllerNamespace = 'backend\modules\order\controllers';

    /**
     * Инициализация модуля
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-module#init()-detail
     */
    public function init()
    {
        parent::init();
        $this->setAliases([
            '@order-assets' => __DIR__ . '/assets'
        ]);


        // custom initialization code goes here
    }
}
