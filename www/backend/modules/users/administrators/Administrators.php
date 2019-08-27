<?php

namespace backend\modules\users\administrators;

use backend\controllers\BaseController;

/**
 * Класс определения модуля 'administrators'
 */
class Administrators extends \yii\base\Module
{
    /**
     *
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-module#$controllerNamespace-detail
     * @var string В свойстве храниться пространство имен модуля
     */
    public $controllerNamespace = 'backend\modules\users\administrators\controllers';

    /**
     * Инициализация модуля
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-module#init()-detail
     */
    public function init()
    {
        parent::init();
        $this->setAliases([
            '@administrators-assets' => __DIR__ . '/assets'
        ]);
//        BaseController::moduleAccess('administrators');
    }
}
