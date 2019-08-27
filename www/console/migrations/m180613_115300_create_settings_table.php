<?php

use backend\modules\settings\models\Settings;
use yii\db\Migration;

/**
 * Handles the creation of table `settings`.
 */
class m180613_115300_create_settings_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('settings', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50),
            'body' => $this->text(),
        ]);

        $arr_lang = [
            [
                'status' => 1,
                'lang' => 'Русский',
                'alias' => 'ru',
            ]
        ];
        $settings = new Settings();
        $settings->name = 'set_language';
        $settings->body = serialize($arr_lang);
        $settings->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('settings');
    }

}
