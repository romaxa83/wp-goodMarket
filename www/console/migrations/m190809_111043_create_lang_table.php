<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%lang}}`.
 */
class m190809_111043_create_lang_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%lang}}', [
            'id' => $this->primaryKey(),
            'alias' => $this->string(255),
            'name' => $this->string(255),
            'priority' => $this->tinyInteger(),
            'status' => $this->tinyInteger(),
        ]);

        // creates index for column `alias`
        $this->createIndex(
            'idx-lang-alias',
            'lang',
            'alias'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `alias`
        $this->dropIndex(
            'idx-lang-alias',
            'lang'
        );
        $this->dropTable('{{%lang}}');
    }
}
