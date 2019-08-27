<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%category_lang}}`.
 */
class m190809_111636_create_category_lang_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%category_lang}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(),
            'lang_id' => $this->integer(),
            'name' => $this->string(255)
        ]);

        // creates index for column `category_id`
        $this->createIndex(
            'idx-category_lang-category_id',
            'category_lang',
            'category_id'
        );

        // creates index for column `lang_id`
        $this->createIndex(
            'idx-category_lang-lang_id',
            'category_lang',
            'lang_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `lang_id`
        $this->dropIndex(
            'idx-category_lang-lang_id',
            'category_lang'
        );
        // drops index for column `category_id`
        $this->dropIndex(
            'idx-category_lang-category_id',
            'category_lang'
        );
        $this->dropTable('{{%category_lang}}');
    }
}
