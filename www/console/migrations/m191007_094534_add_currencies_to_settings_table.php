<?php

use backend\modules\settings\models\Settings;
use yii\db\Migration;

/**
 * Class m191007_094534_add_currency_settings
 */
class m191007_094534_add_currencies_to_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $data = [
            ['currencies', serialize([
                ['name' => 'Гривна', 'alias' => 'грн', 'exchange' => 1],
                ['name' => 'Рубль', 'alias' => 'руб', 'exchange' => 0.378],
                ['name' => 'Доллар', 'alias' => 'usd', 'exchange' => 24.6]
            ])],
        ];

        Yii::$app->db->createCommand()->batchInsert('settings', ['name', 'body'], $data)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        Settings::deleteAll(['name' => ['currencies']]);
    }
}
