<?php

use backend\modules\settings\models\Settings;
use yii\db\Migration;

/**
 * Class m181019_085630_add_settings
 */
class m181019_085630_add_settings extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $data = [
            ['delivery', serialize([
                ['id' => 1, 'name' => 'Новая почта', 'position' => 1, 'status' => 1],
                ['id' => 2, 'name' => 'Курьер', 'position' => 2, 'status' => 1]
            ])],
            ['payment', serialize([
                ['id' => 1, 'name' => 'Наличными', 'position' => 1, 'status' => 1],
                ['id' => 2, 'name' => 'Visa/Mastercard', 'position' => 2, 'status' => 1],
                ['id' => 3, 'name' => 'Приват24', 'position' => 3, 'status' => 1]
            ])],
            ['social-group', serialize([
                ['id' => 1, 'name' => 'facebook', 'link' => NULL, 'status' => 0],
                ['id' => 2, 'name' => 'twitter', 'link' => NULL, 'status' => 0],
                ['id' => 3, 'name' => 'google', 'link' => NULL, 'status' => 0],
                ['id' => 4, 'name' => 'instagram', 'link' => NULL, 'status' => 0]
            ])],
            ['lat', 46.64797788942407],
            ['lng', 32.60101559339864]
        ];

        Yii::$app->db->createCommand()->batchInsert('settings', ['name', 'body'], $data)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        Settings::deleteAll(['name' => ['delivery', 'payment', 'social-group', 'lat', 'lng']]);
    }


}
