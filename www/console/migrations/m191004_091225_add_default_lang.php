<?php

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
            'status' => 1
        ];

        $arrLang[] = [
            'alias' => 'ua',
            'name' => 'Украинский',
            'priority' => 2,
            'status' => 1
        ];

        $arrLang[] = [
            'alias' => 'eng',
            'name' => 'Английский',
            'priority' => 3,
            'status' => 0
        ];

        foreach($arrLang as $oneLang){
            $model = new \common\models\Lang();
            $model->alias = $oneLang['alias'];
            $model->name = $oneLang['name'];
            $model->priority = $oneLang['priority'];
            $model->status = $oneLang['status'];
            
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
        \common\models\Lang()::deleteAll(['in','alias',['ru','ua','eng']]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191004_091225_add_default_lang cannot be reverted.\n";

        return false;
    }
    */
}
