<?php

use common\models\Lang;
use yii\db\Migration;

/**
 * Class m191004_091225_add_default_lang
 */
class m191004_091225_add_default_lang extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $arrLang[] = [
            'alias' => 'ru',
            'name' => 'Русский',
            'priority' => 1,
            'status' => 1,
            'currency' => 'руб'
        ];

        $arrLang[] = [
            'alias' => 'ua',
            'name' => 'Украинский',
            'priority' => 2,
            'status' => 1,
            'currency' => 'грн'
        ];

        $arrLang[] = [
            'alias' => 'eng',
            'name' => 'Английский',
            'priority' => 3,
            'status' => 0,
            'currency' => 'usd'
        ];

        foreach($arrLang as $oneLang){
            $model = new Lang();
            $model->alias = $oneLang['alias'];
            $model->name = $oneLang['name'];
            $model->priority = $oneLang['priority'];
            $model->status = $oneLang['status'];
            $model->currency = $oneLang['currency'];

            if(!$model->save()){
                return 'error';
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Lang::deleteAll(['in','alias',['ru','ua','eng']]);
    }
}
