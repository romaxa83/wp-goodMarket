<?php

namespace backend\modules\banners\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%banner_lang}}`.
 */
class m190830_094008_create_banner_lang_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%banner_lang}}', [
            'id' => $this->primaryKey(),
            'banner_id' => $this->integer(),
            'lang_id' => $this->integer(),
            'media_id' => $this->integer(),
            'alias' => $this->string(255),
            'title' => $this->string(255),
            'text' => $this->text()
        ]);
        // creates index for column `banner_id`
        $this->createIndex(
                'idx-banner_lang-banner_id', 'banner_lang', 'banner_id'
        );

        // creates index for column `lang_id`
        $this->createIndex(
                'idx-banner_lang-lang_id', 'banner_lang', 'lang_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        // drops index for column `banner_id`
        $this->dropIndex(
                'idx-banner_lang-banner_id', 'banner_lang'
        );
        // drops index for column `lang_id`
        $this->dropIndex(
                'idx-banner_lang-lang_id', 'banner_lang'
        );
        $this->dropTable('{{%banner_lang}}');
    }

}
