<?php

namespace backend\modules\category;

use backend\controllers\BaseController;

/**
 * Модуль предназначен для управления пунктов меню. 
 * Модуль позволяет создавать и настраивать разноуровневые меню, включая в них страницы вашего сайта.
 */
class Category extends \yii\base\Module {

    /**
     * @var string Пространство имен, в котором находятся классы контроллеров.
     */
    public $controllerNamespace = 'backend\modules\category\controllers';

    /**
     * Инициализирует объект.
     */
    public function init() {
        parent::init();
        $this->setAliases([
            '@category-assets' => __DIR__ . '/assets'
        ]);
    }

}
