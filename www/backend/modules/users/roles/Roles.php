<?php

namespace backend\modules\users\roles;

use backend\controllers\BaseController;

/**
 * Класс определения модуля 'roles'
 */
class Roles extends \yii\base\Module
{
    /**
     *
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-module#$controllerNamespace-detail
     * @var string В свойстве храниться пространство имен модуля
     */
    public $controllerNamespace = 'backend\modules\users\roles\controllers';

    /**
     * Инициализация модуля
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-module#init()-detail
     */
    public function init()
    {
        parent::init();
        $this->setAliases([
            '@roles-assets' => __DIR__ . '/assets'
        ]);
//        BaseController::moduleAccess('roles');
    }
}
