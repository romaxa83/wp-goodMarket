<?php

namespace backend\modules\settings;

/**
 * settings module definition class
 */
class Settings extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\settings\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->setAliases([
            '@settings-assets' => __DIR__ . '/assets'
        ]);
        // custom initialization code goes here
    }
}
