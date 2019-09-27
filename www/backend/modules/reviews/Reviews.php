<?php

namespace backend\modules\reviews;

use backend\controllers\BaseController;

/**
 * Класс определения модуля 'blog'
 */
class Reviews extends \yii\base\Module
{
    /**
     * В свойстве храниться пространство имен модуля
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-module#$controllerNamespace-detail
     * @var string
     */
    public $controllerNamespace = 'backend\modules\reviews\controllers';

    /**
     * Инициализация модуля
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-module#init()-detail
     */
    public function init()
    {
        parent::init();
        $this->setAliases([
            '@reviews-assets' => __DIR__ . '/assets'
        ]);

//        BaseController::moduleAccess('blog');

        // custom initialization code goes here
    }
}
