<?php

namespace backend\modules\users\people;

use backend\controllers\BaseController;

/**
 * Класс определения модуля 'people'
 */
class People extends \yii\base\Module
{
    /**
     *
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-module#$controllerNamespace-detail
     * @var string В свойстве храниться пространство имен модуля
     */
    public $controllerNamespace = 'backend\modules\users\people\controllers';

    /**
     * Инициализация модуля
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-module#init()-detail
     */
    public function init()
    {
        parent::init();
        $this->setAliases([
            '@people-assets' => __DIR__ . '/assets'
        ]);
//        BaseController::moduleAccess('people');
    }
}
