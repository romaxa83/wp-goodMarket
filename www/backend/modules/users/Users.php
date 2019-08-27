<?php

namespace backend\modules\users;

use yii\base\Module;
use backend\controllers\BaseController;

/**
 * Класс определения модуля 'users'
 */
class Users extends Module
{
    /**
     *
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-module#$controllerNamespace-detail
     * @var string В свойстве храниться пространство имен модуля
     */
    public $controllerNamespace = 'backend\modules\users\controllers';

    /**
     * Инициализация модуля
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-module#init()-detail
     */
    public function init()
    {
        parent::init();
        // custom initialization code goes here
        // BaseController::moduleAccess('users');
    }
}
